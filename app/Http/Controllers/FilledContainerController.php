<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContractContainer;
use App\Enums\ContainerStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Contract;

class FilledContainerController extends Controller
{
    /**
     * Display a listing of filled containers.
     */
    public function index(): View
    {
        $filledContainers = ContractContainer::with(['contract', 'container', 'type'])
            ->where('status', 'filled')
            ->whereNotNull('container_id')
            ->paginate(15);

        return view('filled-containers.index', compact('filledContainers'));
    }

    /**
     * Mark container as filled.
     */
    public function markAsFilled(ContractContainer $contractContainer): RedirectResponse
    {
        if ($contractContainer->container) {
            $contractContainer->container->update(['status' => ContainerStatus::FILLED]);
            $contractContainer->update([
                'status' => 'filled',
                'filled_at' => now()
            ]);
        }

        return redirect()->back()
            ->with('success', __('Container marked as filled successfully.'));
    }

    /**
     * Discharge container (make it available again).
     */
    public function discharge(ContractContainer $contractContainer): RedirectResponse
    {
        if ($contractContainer->container) {
            $contractContainer->container->update(['status' => ContainerStatus::AVAILABLE]);
            $contractContainer->update([
                'status' => 'discharged',
                'discharged_at' => now(),
                'container_id' => null // Remove container assignment
            ]);
        }

        return redirect()->back()
            ->with('success', __('Container discharged successfully.'));
    }

    /**
     * Assign available container to contract.
     */
    public function assignContainer(ContractContainer $contractContainer): RedirectResponse
    {
        // Find available container of the required type
        $availableContainer = Container::where('size_id', $contractContainer->size_id)
            ->where('status', ContainerStatus::AVAILABLE)
            ->first();

        if (!$availableContainer) {
            return redirect()->back()
                ->with('error', __('No available containers of this type found.'));
        }

        // Assign container
        $availableContainer->update(['status' => ContainerStatus::IN_USE]);
        $contractContainer->update([
            'container_id' => $availableContainer->id,
            'status' => 'assigned'
        ]);

        return redirect()->back()
            ->with('success', __('Container assigned successfully.'));
    }
}
