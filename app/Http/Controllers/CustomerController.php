<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getData($request);
        }

        return view('customers.index');
    }
    
    public function getData(Request $request)
    {
        $customers = Customer::query()->orderBy('created_at', 'desc');

        return DataTables::of($customers)
            ->addColumn('type_badge', function ($customer) {
                $badgeClass = $customer->type === 'company' ? 'bg-info' : 'bg-success';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($customer->type) . '</span>';
            })
            ->addColumn('company_info', function ($customer) {
                if ($customer->type === 'company') {
                    $info = '';
                    if ($customer->tax_number) {
                        $info .= '<small class="text-muted d-block">Tax: ' . $customer->tax_number . '</small>';
                    }
                    if ($customer->commercial_number) {
                        $info .= '<small class="text-muted d-block">Commercial: ' . $customer->commercial_number . '</small>';
                    }
                    return $info;
                }
                return '<small class="text-muted">Individual</small>';
            })
            ->addColumn('actions', function ($customer) {
                return $this->getActionButtons($customer);
            })
            ->rawColumns(['type_badge', 'company_info', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,company',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|array',
            'contact_person.name' => 'nullable|string|max:255',
            'contact_person.phone' => 'nullable|string|max:20',
            'contact_person.email' => 'nullable|email|max:255',
            'contact_person.position' => 'nullable|string|max:255',

            'ext' => 'nullable|string|max:10',
            'fax' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'tax_number' => 'nullable|string|max:50',
            'commercial_number' => 'nullable|string|max:50',
        ]);

        // Add conditional validation for company type
        if ($request->type === 'company') {
            $request->validate([
                'tax_number' => 'required|string|max:50',
                'commercial_number' => 'required|string|max:50',
            ]);
        }

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', __('customers.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:individual,company',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'ext' => 'nullable|string|max:10',
            'fax' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'tax_number' => 'nullable|string|max:50',
            'commercial_number' => 'nullable|string|max:50',
        ]);

        // Add conditional validation for company type
        if ($request->type === 'company') {
            $request->validate([
                'tax_number' => 'required|string|max:50',
                'commercial_number' => 'required|string|max:50',
            ]);
        }

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', __('customers.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', __('customers.deleted_successfully'));
    }

    /**
     * Get route prefix for the resource
     */
    protected function getRoutePrefix()
    {
        return 'customers';
    }
}
