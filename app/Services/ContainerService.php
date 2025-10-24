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
     * Create multiple containers with bulk operation
     */
    public function createBulk(array $data): int
    {
        $containers = [];
        $prefix = $data['code_prefix'] ?? '';
        $count = $data['count'];
        $sizeId = $data['size_id'];
        $status = $data['status'];
        $description = $data['description'] ?? null;

        for ($i = 1; $i <= $count; $i++) {
            $code = $prefix . $i;//str_pad($i, 3, '-', STR_PAD_LEFT);
            
            // Check if code already exists
            if (Container::where('code', $code)->exists()) {
                continue;
            }

            $containers[] = [
                'code' => $code,
                'size_id' => $sizeId,
                'status' => $status,
                'description' => $description,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($containers)) {
            Container::insert($containers);
        }

        return count($containers);
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

    /**
     * Get container statistics
     */
    public function getStatistics(): array
    {
        $total = Container::count();
        $byStatus = Container::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'total' => $total,
            'by_status' => $byStatus
        ];
    }

}
