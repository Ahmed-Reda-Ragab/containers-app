<?php

namespace App\Services;

use App\Models\Container;
use App\Enums\ContainerStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContainerService
{
    /**
     * Get all containers with pagination
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Container::latest()->paginate($perPage);
    }

    /**
     * Get all containers without pagination
     */
    public function getAllWithoutPagination(): Collection
    {
        return Container::latest()->get();
    }

    /**
     * Find a container by ID
     */
    public function findById(int $id): ?Container
    {
        return Container::find($id);
    }

    /**
     * Create a new container
     */
    public function create(array $data): Container
    {
        return Container::create($data);
    }

    /**
     * Update a container
     */
    public function update(Container $container, array $data): bool
    {
        return $container->update($data);
    }

    /**
     * Delete a container
     */
    public function delete(Container $container): bool
    {
        return $container->delete();
    }

    /**
     * Get available container statuses
     */
    public function getStatuses(): array
    {
        return collect(ContainerStatus::cases())
            ->mapWithKeys(fn($status) => [$status->value => $status->label()])
            ->toArray();
    }

}
