<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Type;
use App\Services\ContainerService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContainerController extends Controller
{
    protected ContainerService $containerService;

    public function __construct(ContainerService $containerService)
    {
        $this->containerService = $containerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $containers = $this->containerService->getAll();
        return view('containers.index', compact('containers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $statuses = $this->containerService->getStatuses();
        $types = Type::all();
        return view('containers.create', compact('statuses', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:containers,code',
            'type_id' => 'required|exists:types,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->containerService->create($validated);

        return redirect()->route('containers.index')
            ->with('success', 'Container created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Container $container): View
    {
        return view('containers.show', compact('container'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Container $container): View
    {
        $statuses = $this->containerService->getStatuses();
        $types = Type::all();
        return view('containers.edit', compact('container', 'statuses', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Container $container): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:containers,code,' . $container->id,
            'type_id' => 'required|exists:types,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->containerService->update($container, $validated);

        return redirect()->route('containers.index')
            ->with('success', 'Container updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Container $container): RedirectResponse
    {
        $this->containerService->delete($container);

        return redirect()->route('containers.index')
            ->with('success', 'Container deleted successfully.');
    }
}
