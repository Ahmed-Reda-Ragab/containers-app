<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['contract', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contracts = Contract::with('customer')->get();
        return view('payments.create', compact('contracts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'payed' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'is_payed' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        DB::beginTransaction();
        try {
            $payment = Payment::create($validated);

            // Update contract total_payed
            $contract = Contract::findOrFail($validated['contract_id']);
            $contract->increment('total_payed', $validated['payed']);

            DB::commit();

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Payment recorded successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('Failed to record payment. Please try again.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['contract.customer', 'user']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $contracts = Contract::with('customer')->get();
        return view('payments.edit', compact('payment', 'contracts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'payed' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'is_payed' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $oldAmount = $payment->payed;
            $newAmount = $validated['payed'];
            $difference = $newAmount - $oldAmount;

            $payment->update($validated);

            // Update contract total_payed
            $contract = Contract::findOrFail($validated['contract_id']);
            $contract->increment('total_payed', $difference);

            DB::commit();

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Payment updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('Failed to update payment. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $contract = $payment->contract;
            $contract->decrement('total_payed', $payment->payed);
            
            $payment->delete();
            
            DB::commit();

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Payment deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Failed to delete payment. Please try again.'));
        }
    }

    /**
     * Create payment for specific contract
     */
    public function createForContract(Contract $contract)
    {
        return view('payments.create', compact('contract'));
    }
}
