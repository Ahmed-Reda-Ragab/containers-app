<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReceiptController extends Controller
{
    /**
     * Display a listing of receipts
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Receipt::with(['contract', 'customer', 'issuedBy', 'collectedBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('issue_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('issue_date', '<=', $dateTo);
        }

        $receipts = $query->orderBy('issue_date', 'desc')->paginate(50);

        $receiptStats = [
            'total_issued' => Receipt::where('status', 'issued')->count(),
            'total_collected' => Receipt::where('status', 'collected')->count(),
            'total_overdue' => Receipt::where('status', 'overdue')->count(),
            'total_amount_issued' => Receipt::where('status', 'issued')->sum('amount'),
            'total_amount_collected' => Receipt::where('status', 'collected')->sum('amount'),
            'total_amount_overdue' => Receipt::where('status', 'overdue')->sum('amount'),
        ];

        return view('receipts.index', compact('receipts', 'receiptStats', 'status', 'dateFrom', 'dateTo'));
    }

    /**
     * Show the form for creating a new receipt
     */
    public function create()
    {
        $contracts = Contract::with(['customer'])->get();
        $customers = Customer::all();

        return view('receipts.create', compact('contracts', 'customers'));
    }

    /**
     * Store a newly created receipt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'customer_id' => 'required|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $validated['receipt_number'] = $this->generateReceiptNumber();
            $validated['issue_date'] = now()->toDateString();
            $validated['issued_by'] = Auth::id();

            $receipt = Receipt::create($validated);

            return redirect()->route('receipts.show', $receipt)
                ->with('success', __('Receipt created successfully.'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', __('Failed to create receipt. Please try again.'));
        }
    }

    /**
     * Display the specified receipt
     */
    public function show(Receipt $receipt)
    {
        $receipt->load(['contract', 'customer', 'issuedBy', 'collectedBy']);

        return view('receipts.show', compact('receipt'));
    }

    /**
     * Show the form for editing the specified receipt
     */
    public function edit(Receipt $receipt)
    {
        $contracts = Contract::with(['customer'])->get();
        $customers = Customer::all();

        return view('receipts.edit', compact('receipt', 'contracts', 'customers'));
    }

    /**
     * Update the specified receipt
     */
    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'customer_id' => 'required|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:issued,collected,overdue,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $receipt->update($validated);

            return redirect()->route('receipts.show', $receipt)
                ->with('success', __('Receipt updated successfully.'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', __('Failed to update receipt. Please try again.'));
        }
    }

    /**
     * Remove the specified receipt
     */
    public function destroy(Receipt $receipt)
    {
        try {
            $receipt->delete();

            return redirect()->route('receipts.index')
                ->with('success', __('Receipt deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to delete receipt. Please try again.'));
        }
    }

    /**
     * Mark receipt as collected
     */
    public function collect(Receipt $receipt)
    {
        try {
            $receipt->update([
                'status' => 'collected',
                'collection_date' => now()->toDateString(),
                'collected_by' => Auth::id(),
            ]);

            return redirect()->route('receipts.show', $receipt)
                ->with('success', __('Receipt marked as collected successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to mark receipt as collected. Please try again.'));
        }
    }

    /**
     * Print receipt
     */
    public function print(Receipt $receipt)
    {
        $receipt->load(['contract', 'customer', 'issuedBy', 'collectedBy']);

        return view('receipts.print', compact('receipt'));
    }

    /**
     * Generate unique receipt number
     */
    private function generateReceiptNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $prefix = "RCP-{$year}{$month}";
        
        $lastReceipt = Receipt::where('receipt_number', 'like', "{$prefix}%")
            ->orderBy('receipt_number', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}