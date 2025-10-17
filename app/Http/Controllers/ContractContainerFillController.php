<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractContainerFill;
use App\Models\Container;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ContainerStatus;
use App\Enums\ContractStatus;

class ContractContainerFillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fills = ContractContainerFill::with(['contract.customer', 'container', 'deliver', 'discharge', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('contract-container-fills.index', compact('fills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contracts = Contract::with('customer')->get();
        $containers = Container::where('status', 'available')->get();
        $customers = Customer::all();
        $users = User::all();

        return view('contract-container-fills.create', compact('contracts', 'containers', 'customers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $contract = Contract::findOrFail($request->contract_id);
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'container_id' => 'required|exists:containers,id,size_id,' . $contract->type_id.',status,'.ContainerStatus::AVAILABLE->value,
            // 'no' => 'required|integer|min:1',
            'deliver_id' => 'required|exists:users,id',
            'deliver_at' => 'required|date',
            'expected_discharge_date' => 'required|date',
            'price' => 'nullable|numeric|min:0',
            // 'client_id' => 'required|exists:customers,id',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $validated['client_id'] = $contract->customer_id;
            $validated['price'] = $contract->priceForNextContainer();
            $fill = ContractContainerFill::create($validated);

            // Update container status to 'in_use' (delivered to client)
            $container = Container::findOrFail($validated['container_id']);
            $container->update(['status' => 'in_use']);

            return redirect()->route('contracts.show', $fill->contract)
                ->with('success', __('Container delivered successfully.'));
        } catch (\Exception $e) {
            dd($e);
            return back()->withInput()
                ->with('error', __('Failed to record container delivery. Please try again.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractContainerFill $contractContainerFill)
    {
        $contractContainerFill->load(['contract.customer', 'container', 'deliver', 'discharge', 'client']);
        return view('contract-container-fills.show', compact('contractContainerFill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractContainerFill $contractContainerFill)
    {
        $contracts = Contract::with('customer')->get();
        $containers = Container::all();
        $customers = Customer::all();
        $users = User::all();

        return view('contract-container-fills.edit', compact('contractContainerFill', 'contracts', 'containers', 'customers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractContainerFill $contractContainerFill)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'no' => 'required|integer|min:1',
            'deliver_id' => 'required|exists:users,id',
            'deliver_at' => 'required|date',
            'container_id' => 'required|exists:containers,id',
            'expected_discharge_date' => 'required|date',
            'discharge_date' => 'nullable|date',
            'discharge_id' => 'nullable|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'client_id' => 'required|exists:customers,id',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $contractContainerFill->update($validated);

            return redirect()->route('contracts.show', $contractContainerFill->contract)
                ->with('success', __('Container fill updated successfully.'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', __('Failed to update container fill. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractContainerFill $contractContainerFill)
    {
        try {
            $contract = $contractContainerFill->contract;
            
            // Update container status back to 'available'
            $container = $contractContainerFill->container;
            $container->update(['status' => 'available']);
            
            $contractContainerFill->delete();

            return redirect()->route('contracts.show', $contract)
                ->with('success', __('Container fill deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to delete container fill. Please try again.'));
        }
    }

    /**
     * Create container fill for specific contract
     */
    public function createForContract(Contract $contract)
    {
        $containers = Container::where(['status'=>ContainerStatus::AVAILABLE->value , 'size_id'=>$contract->type_id])->get();
        $customers = [];//Customer::all();
        $users = User::all();
        $contracts = [$contract];//Contract::all();
        return view('contract-container-fills.create', compact('contract', 'containers', 'customers', 'users' , 'contracts'));
    }

    /**
     * Mark container as filled (ready for pickup)
     */
    public function markAsFilled(Request $request, ContractContainerFill $contractContainerFill)
    {
        try {
            // Update container status to 'filled' (ready for pickup)
            $container = $contractContainerFill->container;
            $container->update(['status' => 'filled']);

            return redirect()->route('contracts.show', $contractContainerFill->contract)
                ->with('success', __('Container marked as filled and ready for pickup.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to mark container as filled. Please try again.'));
        }
    }

    /**
     * Mark container as discharged (picked up and emptied)
     */
    public function discharge(Request $request, ContractContainerFill $contractContainerFill)
    {
        abort_if($contractContainerFill->is_discharged, 403, __('Container is already discharged.'));
        $validated = $request->validate([
            'discharge_date' => 'required|date',
            'discharge_id' => 'required|exists:users,id',
        ]);

        try {
            $contractContainerFill->update($validated);

            // Update container status back to 'available' (emptied and ready for reuse)
            $container = $contractContainerFill->container;
            $container->update(['status' => ContainerStatus::AVAILABLE->value]);

            return redirect()->route('contracts.show', $contractContainerFill->contract)
                ->with('success', __('Container discharged and emptied successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to discharge container. Please try again.'));
        }
    }

    /**
     * Replace container (deliver new empty container, pick up filled one)
     */
    public function replaceContainer(Request $request, ContractContainerFill $contractContainerFill)
    {
        $validated = $request->validate([
            'new_container_id' => 'required|exists:containers,id',
            'replace_date' => 'required|date',
            'replace_user_id' => 'required|exists:users,id',
        ]);

        try {
            // Update the container fill with new container
            $contractContainerFill->update([
                'container_id' => $validated['new_container_id'],
                'discharge_date' => $validated['replace_date'],
                'discharge_id' => $validated['replace_user_id'],
            ]);

            // Set old container to available (emptied)
            $oldContainer = Container::find($contractContainerFill->getOriginal('container_id'));
            if ($oldContainer) {
                $oldContainer->update(['status' => 'available']);
            }

            // Set new container to in_use
            $newContainer = Container::find($validated['new_container_id']);
            $newContainer->update(['status' => 'in_use']);

            return redirect()->route('contracts.show', $contractContainerFill->contract)
                ->with('success', __('Container replaced successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to replace container. Please try again.'));
        }
    }
}
