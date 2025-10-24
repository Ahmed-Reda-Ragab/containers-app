<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Customer;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferCrudController extends Controller
{
    public function index()
    {
        $offers = Offer::orderByDesc('created_at')->paginate(15);
        return view('offers.index', compact('offers'));
    }

    public function create()
    {
        $customers = Customer::all();
        $types = Type::all();
        return view('offers.create', compact('customers', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer' => 'required|array',
            'customer.name' => 'required|string|max:255',
            'customer.*' => 'nullable|string|max:255',
            'size_id' => 'required|exists:sizes,id',
            'container_price' => 'required|numeric|min:0',
            'no_containers' => 'required|integer|min:1',
            'monthly_dumping_cont' => 'required|numeric|min:1',
            'additional_trip_cost' => 'required|numeric|min:0',
            'contract_period' => 'required|integer|min:1',
            'tax_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'agreement_terms' => 'nullable|string',
            'material_restrictions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_policy' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
        ]);

        $validated['monthly_total_dumping_cost'] = $validated['container_price'] * $validated['no_containers'] * $validated['monthly_dumping_cont'];
        $subtotal = $validated['monthly_total_dumping_cost'];// + $validated['additional_trip_cost'];
        $validated['total_price'] = $subtotal + ($subtotal * ($validated['tax_value'] / 100));
        $validated['user_id'] = Auth::id();

        $offer = Offer::create($validated);

        return redirect()->route('offers.show', $offer)->with('success', __('Offer created.'));
    }

    public function show(Offer $offer)
    {
        return view('offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $customers = Customer::all();
        $types = Type::all();
        return view('offers.edit', compact('offer', 'customers', 'types'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer' => 'required|array',
            'customer.name' => 'required|string|max:255',
            'size_id' => 'required|exists:sizes,id',
            'container_price' => 'required|numeric|min:0',
            'no_containers' => 'required|integer|min:1',
            'monthly_dumping_cont' => 'required|numeric|min:0',
            'additional_trip_cost' => 'required|numeric|min:0',
            'contract_period' => 'required|integer|min:1',
            'tax_value' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'agreement_terms' => 'nullable|string',
            'material_restrictions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_policy' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
        ]);

        $validated['monthly_total_dumping_cost'] = $validated['container_price'] * $validated['no_containers'];
        $subtotal = $validated['monthly_total_dumping_cost'] + $validated['additional_trip_cost'];
        $validated['total_price'] = $subtotal + ($subtotal * ($validated['tax_value'] / 100));

        $offer->update($validated);
        return redirect()->route('offers.show', $offer)->with('success', __('Offer updated.'));
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index')->with('success', __('Offer deleted.'));
    }
}


