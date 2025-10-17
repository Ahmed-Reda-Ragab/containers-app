<?php

namespace App\Traits;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

trait DataTableTrait
{
    /**
     * Get DataTable response for server-side processing
     */
    public function getDataTableResponse($query, $columns = [])
    {
        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return $this->getActionButtons($row);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($row)
    {
        $viewUrl = route($this->getRoutePrefix() . '.show', $row->id);
        $editUrl = route($this->getRoutePrefix() . '.edit', $row->id);
        $deleteUrl = route($this->getRoutePrefix() . '.destroy', $row->id);

        return '
            <div class="btn-group" role="group">
                <a href="' . $viewUrl . '" class="btn btn-sm btn-outline-info" title="' . __('View') . '">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning" title="' . __('Edit') . '">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="' . $deleteUrl . '" class="btn btn-sm btn-outline-danger delete-btn" 
                   title="' . __('Delete') . '" data-id="' . $row->id . '">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        ';
    }

    /**
     * Get route prefix for the resource
     * Override this method in your controller
     */
    protected function getRoutePrefix()
    {
        return 'customers';
    }
}
