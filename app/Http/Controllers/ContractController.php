<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Container;
use App\Enums\ContainerStatus;
use App\Models\Offer;
use App\Enums\OfferStatus;

class ContractController extends Controller
{
    /**
     * Quick creation page for Individual contracts: customer lookup, container fill, payment, receipt.
     */
    public function quickIndividual(Request $request)
    {
        $types = Type::all();
        $drivers = User::drivers()->get();
        $availableContainers = Container::where('status', ContainerStatus::AVAILABLE->value)
            ->with('size')
            ->get();
        return view('contracts.quick-individual', compact('types', 'drivers', 'availableContainers'));
    }

    /**
     * Handle quick creation for Individual contract in one submission.
     */
    public function quickIndividualStore(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer.name' => 'required_without:customer_id|string|max:255',
            'customer.mobile' => 'nullable|string|max:20',
            'customer.city' => 'nullable|string|max:100',
            'customer.address' => 'nullable|string|max:500',
            'size_id' => 'required|exists:sizes,id',
            'container_price' => 'required|numeric|min:0',
            'tax_value' => 'required|numeric|min:0|max:100',
            'additional_trip_cost' => 'nullable|numeric|min:0',
            'contract_period' => 'nullable|integer|min:1',
            'monthly_dumping_cont' => 'nullable|numeric|min:1',
            'no_containers' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // Fill
            'container_id' => 'required|exists:containers,id',
            'deliver_id' => 'required|exists:users,id',
            'deliver_at' => 'required|date',
            'expected_discharge_date' => 'required|date|after_or_equal:deliver_at',
            'fill_city' => 'required|string|max:100',
            'fill_address' => 'required|string|max:500',
            // Payment (optional)
            'payment.amount' => 'nullable|numeric|min:0.01',
            'payment.method' => 'nullable|string|max:100',
            'payment.is_payed' => 'nullable|boolean',
            // Receipt (optional)
            'receipt.create' => 'nullable|boolean',
            'receipt.due_date' => 'nullable|date|after:today',
            'receipt.notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($request) {
            // Resolve or create customer
            $customerId = $request->integer('customer_id');
            if (!$customerId) {
                $newCustomer = Customer::create([
                    'name' => data_get($request, 'customer.name'),
                    'type' => 'individual',
                    'mobile' => data_get($request, 'customer.mobile'),
                    'city' => data_get($request, 'customer.city'),
                    'address' => data_get($request, 'customer.address'),
                ]);
                $customerId = $newCustomer->id;
            }

            // Build contract payload
            $contractData = [
                'customer_id' => $customerId,
                'type' => 'individual',
                'customer' => [
                    'name' => data_get($request, 'customer.name'),
                    'mobile' => data_get($request, 'customer.mobile'),
                    'city' => data_get($request, 'customer.city'),
                    'address' => data_get($request, 'customer.address'),
                ],
                'size_id' => $request->integer('size_id'),
                'container_price' => $request->input('container_price'),
                'no_containers' => $request->integer('no_containers', 1),
                'monthly_dumping_cont' => (float) $request->input('monthly_dumping_cont', 1),
                'additional_trip_cost' => $request->input('additional_trip_cost', $request->input('container_price')),
                'contract_period' => $request->integer('contract_period', 1),
                'tax_value' => $request->input('tax_value'),
                'start_date' => $request->date('start_date'),
                'end_date' => $request->date('end_date'),
                'status' => 'active',
                'user_id' => Auth::id(),
            ];

            // Compute price with VAT
            $containerPrice = (float) $contractData['container_price'];
            $taxPercent = (float) $contractData['tax_value'];
            $contractData['container_price_w_vat'] = round($containerPrice * (1 + ($taxPercent / 100)), 2);

            // Totals similar to store()
            $monthlyTotalDumpingCost = $contractData['container_price'] * $contractData['no_containers'];
            $subtotal = $monthlyTotalDumpingCost + $contractData['additional_trip_cost'];
            $taxAmount = $subtotal * ($contractData['tax_value'] / 100);
            $totalPrice = $subtotal + $taxAmount;
            $contractData['monthly_total_dumping_cost'] = $monthlyTotalDumpingCost;
            $contractData['total_price'] = $totalPrice;

            $contract = Contract::create($contractData);

            // Create one fill
            $container = Container::where([
                'id' => $request->integer('container_id'),
                'status' => ContainerStatus::AVAILABLE->value,
                'size_id' => $contract->size_id,
            ])->firstOrFail();

            $fill = $contract->contractContainerFills()->create([
                'container_id' => $container->id,
                'deliver_id' => $request->integer('deliver_id'),
                'deliver_at' => $request->date('deliver_at'),
                'expected_discharge_date' => $request->date('expected_discharge_date'),
                'price' => $contract->priceForNextContainer(),
                'client_id' => $customerId,
                'city' => $request->string('fill_city'),
                'address' => $request->string('fill_address'),
                'notes' => null,
            ]);
            // Mark container in_use
            $container->update(['status' => 'in_use']);

            // Optional payment
            if ($request->filled('payment.amount')) {
                $contract->payments()->create([
                    'user_id' => Auth::id(),
                    'payed' => $request->input('payment.amount'),
                    'method' => $request->input('payment.method'),
                    'is_payed' => (bool) $request->input('payment.is_payed', true),
                ]);
                $contract->increment('total_paid', (float) $request->input('payment.amount'));
            }

            // Optional receipt for this single fill
            if ((bool) $request->input('receipt.create', false) && $request->filled('receipt.due_date')) {
                $amount = $fill->price;
                // generate receipt number
                $year = now()->year;
                $month = now()->format('m');
                $prefix = "RCP-{$year}{$month}";
                $last = \App\Models\Receipt::where('receipt_number', 'like', "{$prefix}%")
                    ->orderBy('receipt_number', 'desc')
                    ->first();
                $seq = $last ? ((int) substr($last->receipt_number, -4)) + 1 : 1;
                $receiptNumber = $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
                $receipt = \App\Models\Receipt::create([
                    'receipt_number' => $receiptNumber,
                    'contract_id' => $contract->id,
                    'customer_id' => $customerId,
                    'customer_name' => $contract->customer['name'] ?? '',
                    'customer_phone' => $contract->customer['mobile'] ?? '',
                    'customer_address' => $contract->customer['address'] ?? '',
                    'city' => $contract->customer['city'] ?? '',
                    'amount' => $amount,
                    'status' => 'issued',
                    'issue_date' => now()->toDateString(),
                    'due_date' => $request->input('receipt.due_date'),
                    'issued_by' => Auth::id(),
                    'notes' => $request->input('receipt.notes'),
                ]);
                // link fill to receipt
                $fill->update(['receipt_id' => $receipt->id]);
                // update contract total
                $contract->increment('total', $amount);
            }

            // Return JSON for AJAX requests, redirect for regular requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Contract, delivery, and optional payment/receipt created.'),
                    'url' => route('contracts.show', $contract)
                ]);
            }

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Contract, delivery, and optional payment/receipt created.'));
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ?string $type = null)
    {
        $contracts = Contract::with(['customer', 'size', 'user', 'payments'])
            ->when($type, function ($q) use ($type) {
                $q->where('type', strtolower($type) === 'individual' ? 'individual' : 'business');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('contracts.index', compact('contracts', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, ?string $type = null)
    {
        $customers = Customer::all();
        $types = Type::all();
        $users = User::all();

        return view('contracts.create', compact('customers', 'types', 'users', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'customer' => 'required|array',
            'type' => 'required|in:business,individual',
            'customer.name' => 'required|string|max:255',
            'customer.contact_person' => 'nullable|string|max:255',
            'customer.mobile' => 'nullable|string|max:20',
            'customer.city' => 'nullable|string|max:100',
            'customer.address' => 'nullable|string|max:500',
            'customer.*' => 'nullable|string|max:255',
            'size_id' => 'required|exists:sizes,id',
            'container_price' => 'required|numeric|min:0',
            'no_containers' => 'required|integer|min:1',
            'monthly_dumping_cont' => 'required|numeric|min:0',
            // 'dumping_cost' => 'required|numeric|min:0',
            'additional_trip_cost' => 'required|numeric|min:0',
            'contract_period' => 'required|integer|min:1',
            'tax_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,active,expired,canceled',
            'notes' => 'nullable|string',
            'agreement_terms' => 'nullable|string',
            'material_restrictions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_policy' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
        ]);
        // dd($validated);

        // Compute price with VAT
        $validated['container_price_w_vat'] = round($validated['container_price'] * (1 + ($validated['tax_value'] / 100)), 2);

        // Calculate totals
        $validated['additional_trip_cost'] = isset($validated['additional_trip_cost']) && $validated['additional_trip_cost'] > 0 ? $validated['additional_trip_cost'] : $validated['container_price'];
        $monthlyTotalDumpingCost = $validated['container_price'] * $validated['no_containers'];
        $subtotal = $monthlyTotalDumpingCost + $validated['additional_trip_cost'];
        $taxAmount = $subtotal * ($validated['tax_value'] / 100);
        $totalPrice = $subtotal + $taxAmount;
        
        $validated['monthly_total_dumping_cost'] = $monthlyTotalDumpingCost;
        $validated['total_price'] = $totalPrice;
        $validated['user_id'] = Auth::id();

        DB::beginTransaction();
        try {
            $contract = Contract::create($validated);
            DB::commit();

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Contract created successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->withInput()
                ->with('error', __('Failed to create contract. Please try again.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $users = User::all();
        $availableContainers = Container::where(['status'=>ContainerStatus::AVAILABLE->value , 'size_id'=>$contract->size_id])->with('size')->get();
        $contract->load(['customer', 'size', 'user', 'payments.user', 'contractContainerFills.container', 'contractContainerFills.deliver', 'contractContainerFills.discharge', 'contractContainerFills.client', 'contractContainerFills.receipt']);

        return view('contracts.show', compact('contract', 'users', 'availableContainers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $customers = Customer::all();
        $types = Type::all();
        $users = User::all();

        return view('contracts.edit', compact('contract', 'customers', 'types', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer' => 'required|array',
            'customer.name' => 'required|string|max:255',
            'customer.contact_person' => 'nullable|string|max:255',
            'customer.telephone' => 'nullable|string|max:20',
            'customer.ext' => 'nullable|string|max:10',
            'customer.fax' => 'nullable|string|max:20',
            'customer.mobile' => 'nullable|string|max:20',
            'customer.city' => 'nullable|string|max:100',
            'customer.address' => 'nullable|string|max:500',
            'size_id' => 'required|exists:sizes,id',
            'container_price' => 'required|numeric|min:0',
            'no_containers' => 'required|integer|min:1',
            'monthly_dumping_cont' => 'required|numeric|min:0',
            'dumping_cost' => 'required|numeric|min:0',
            'additional_trip_cost' => 'required|numeric|min:0',
            'contract_period' => 'required|integer|min:1',
            'tax_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,active,expired,canceled',
            'notes' => 'nullable|string',
            'agreement_terms' => 'nullable|string',
            'material_restrictions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_policy' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
        ]);

        // Compute price with VAT
        $validated['container_price_w_vat'] = round($validated['container_price'] * (1 + ($validated['tax_value'] / 100)), 2);

        // Calculate totals
        $monthlyTotalDumpingCost = $validated['dumping_cost'] * $validated['no_containers'] * $validated['monthly_dumping_cont'];
        $subtotal = $monthlyTotalDumpingCost;// + $validated['additional_trip_cost'];
        $taxAmount = $subtotal * ($validated['tax_value'] / 100);
        $totalPrice = $subtotal + $taxAmount;

        $validated['monthly_total_dumping_cost'] = $monthlyTotalDumpingCost;
        $validated['total_price'] = $totalPrice;

        DB::beginTransaction();
        try {
            $contract->update($validated);
            DB::commit();

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Contract updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('Failed to update contract. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        try {
            $contract->delete();
            return redirect()->route('contracts.index')
                ->with('success', __('Contract deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to delete contract. Please try again.'));
        }
    }

    /**
     * Get customer data for AJAX auto-fill
     */
    public function getCustomerData(Customer $customer)
    {
        return response()->json([
            'name' => $customer->name,
            'contact_person' => $customer->contact_person,
            'telephone' => $customer->telephone,
            'ext' => $customer->ext,
            'fax' => $customer->fax,
            'mobile' => $customer->mobile,
            'city' => $customer->city,
            'address' => $customer->address,
        ]);
    }

    /**
     * Convert offer to contract
     */
    public function convertFromOffer(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
        ]);

        $offer = Offer::findOrFail($request->integer('offer_id'));

        // Redirect to contracts.create with offer id in query to prefill
        // and update offer status to accepted
        $offer->update(['status' => OfferStatus::ACCEPTED->value]);

        return redirect()->route('contracts.create', ['offer_id' => $offer->id])
            ->with('success', __('Offer converted. Prefilled contract form loaded.'));
    }

    /**
     * Print contract
     */
    public function print(Contract $contract)
    {
        $contract->load(['customer', 'size', 'user']);
        return view('contracts.print', compact('contract'));
    }
}
