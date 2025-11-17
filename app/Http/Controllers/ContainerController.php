<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Type;
use App\Rules\UniqueContainerRange;
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
    public function index(Request $request): View
    {
        $filters = array_filter(
            $request->only((new Container())->getFillable()),
            fn ($value) => $value !== null && $value !== ''
        );

        $containers = $this->containerService->getAll(null, $filters);
        $sizes = Type::all();
        $statistics = $this->containerService->getStatistics();
        return view('containers.index', compact('containers', 'sizes', 'statistics', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $statuses = $this->containerService->getStatuses();
        $sizes = Type::all();
        return view('containers.create', compact('statuses', 'sizes'));
    }

    /**
     * Show the form for creating multiple containers.
     */
    public function createBulk(): View
    {
        $statuses = $this->containerService->getStatuses();
        $sizes = Type::all();
        return view('containers.create-bulk', compact('statuses', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:containers,code',
            'size_id' => 'required|exists:sizes,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->containerService->create($validated);

        return redirect()->route('containers.index')
            ->with('success', __('containers.created_successfully'));
    }

    /**
     * Store multiple containers in storage.
     */
    public function storeBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code_prefix' => 'nullable|string|max:50',
            'from_count' => 'required|integer|min:1|max:999999',
            // 'to_count' => 'required|integer|min:'.($request->from_count??1).'|max:9999999',
            'to_count' => [
                'required',
                'integer',
                'min:' . ($request->from_count ?? 1),
                'max:9999999',
                new UniqueContainerRange(
                    $request->code_prefix,
                    $request->from_count,
                    $request->to_count,
                    $request->size_id
                ),
            ],
            // 'count' => 'required|integer|min:1|max:100',
            'size_id' => 'required|exists:sizes,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $createdCount = $this->containerService->createBulk($validated);

        return redirect()->route('containers.index')
            ->with('success', __('containers.created_bulk_successfully', ['count' => $createdCount]));
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
        $sizes = Type::all();
        return view('containers.edit', compact('container', 'statuses', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Container $container): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:containers,code,' . $container->id,
            'size_id' => 'required|exists:sizes,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->containerService->update($container, $validated);

        return redirect()->route('containers.index')
            ->with('success', __('containers.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Container $container): RedirectResponse
    {
        $this->containerService->delete($container);

        return redirect()->route('containers.index')
            ->with('success', __('containers.deleted_successfully'));
    }
}
