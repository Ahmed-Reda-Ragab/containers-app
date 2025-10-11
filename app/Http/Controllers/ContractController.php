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

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = Contract::with(['customer', 'type', 'user', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $types = Type::all();
        $users = User::all();

        return view('contracts.create', compact('customers', 'types', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            'type_id' => 'required|exists:types,id',
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
        // dd($validated);

        // Calculate totals
        $monthlyTotalDumpingCost = $validated['dumping_cost'] * $validated['no_containers'];
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
        $availableContainers = Container::where('status', 'available')->with('type')->get();
        $contract->load(['customer', 'type', 'user', 'payments.user', 'contractContainerFills.container', 'contractContainerFills.deliver', 'contractContainerFills.discharge', 'contractContainerFills.client']);

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
            'type_id' => 'required|exists:types,id',
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

        // Calculate totals
        $monthlyTotalDumpingCost = $validated['dumping_cost'] * $validated['no_containers'];
        $subtotal = $monthlyTotalDumpingCost + $validated['additional_trip_cost'];
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
        // This would handle converting an offer to a contract
        // Implementation depends on your offer system
        return redirect()->route('contracts.create')
            ->with('info', __('Please create a new contract.'));
    }

    /**
     * Print contract
     */
    public function print(Contract $contract)
    {
        $contract->load(['customer', 'type', 'user']);
        return view('contracts.print', compact('contract'));
    }
}
