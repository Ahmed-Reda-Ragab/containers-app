@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                                    <p class="mb-0">{{ __('containers.total_containers') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-boxes fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($statistics['by_status'] as $status => $count)
                    <div class="col-md-3">
                        <div class="card bg-{{ $status === 'available' ? 'success' : ($status === 'in_use' ? 'warning' : 'danger') }} text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0">{{ $count }}</h4>
                                        <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $status)) }}</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-{{ $status === 'available' ? 'check-circle' : ($status === 'in_use' ? 'clock' : 'exclamation-triangle') }} fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    
                <h4 class="mb-0">{{ __('containers.title') }}</h4>
                </div>
                <div class="card-toolbar">

                    <div class="btn-group">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#typesModal">
                            <i class="fas fa-cog"></i> {{ __('Manage Types') }}
                        </button>
                        <a href="{{ route('containers.create-bulk') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('containers.create_bulk') }}
                        </a>
                        <a href="{{ route('containers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('containers.add_new_container') }}
                        </a>
                    </div>
                </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($containers->count() > 0)
                        <x-datatable 
                            id="containersTable"
                            :columns="[
                                ['title' => __('ID'), 'data' => 'id'],
                                ['title' => __('containers.code'), 'data' => 'code'],
                                ['title' => __('containers.size'), 'data' => 'size'],
                                ['title' => __('containers.status'), 'data' => 'status'],
                                ['title' => __('containers.description'), 'data' => 'description'],
                                ['title' => __('Created At'), 'data' => 'created_at'],
                                ['title' => __('containers.actions'), 'data' => 'actions']
                            ]"
                            :searchable="true"
                            :filterable="true"
                            :filters="[
                                [
                                    'name' => 'size',
                                    'placeholder' => __('containers.filter_by_size'),
                                    'column' => 2,
                                    'options' => $sizes->pluck('name', 'name')->toArray()
                                ]
                            ]"
                            :language="app()->getLocale()"
                        >
                            @foreach($containers as $container)
                                <tr>
                                    <td>{{ $container->id }}</td>
                                    <td>
                                        <strong>{{ $container->code }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $container->size->name ?? '' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $container->status->color() }}">{{ $container->status->label() }}</span>
                                    </td>
                                    <td>
                                        @if($container->description)
                                            {{ Str::limit($container->description, 50) }}
                                        @else
                                            <span class="text-muted">{{ __('No description') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $container->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('containers.show', $container) }}" class="btn btn-sm btn-outline-info" title="{{ __('containers.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('containers.edit', $container) }}" class="btn btn-sm btn-outline-warning" title="{{ __('containers.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('containers.destroy', $container) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('containers.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('containers.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-datatable>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('containers.no_containers') }}</h5>
                            <p class="text-muted">{{ __('containers.get_started') }}</p>
                            <a href="{{ route('containers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('containers.add_new_container') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Sizes Management Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" aria-labelledby="sizesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sizesModalLabel">{{ __('Manage Container Sizes') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>{{ __('Container Sizes') }}</h6>
                    <button type="button" class="btn btn-success btn-sm" id="addSizeBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Size') }}
                    </button>
                </div>
                
                <div id="sizesList">
                    <!-- Sizes will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Size Modal -->
<div class="modal fade" id="sizeFormModal" tabindex="-1" aria-labelledby="sizeFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sizeFormModalLabel">{{ __('Add Size') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sizeForm">
                    @csrf
                    <input type="hidden" id="sizeId" name="size_id">
                    <div class="mb-3">
                        <label for="sizeName" class="form-label">{{ __('Size Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sizeName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('containers.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveSizeBtn">{{ __('containers.save') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="typesModal" tabindex="-1" aria-labelledby="typesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typesModalLabel">{{ __('Manage Container Types') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>{{ __('Container Types') }}</h6>
                    <button type="button" class="btn btn-success btn-sm" id="addTypeBtn">
                        <i class="fas fa-plus"></i> {{ __('Add Type') }}
                    </button>
                </div>
                
                <div id="typesList">
                    <!-- Types will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="typeFormModal" tabindex="-1" aria-labelledby="typeFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeFormModalLabel">{{ __('Add Type') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="typeForm">
                    @csrf
                    <input type="hidden" id="typeId" name="size_id">
                    <div class="mb-3">
                        <label for="typeName" class="form-label">{{ __('Type Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="typeName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveTypeBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // Initialize both sections
    initEntityHandlers('type', '/types');
    initEntityHandlers('size', '/sizes');

    /**
     * Initialize CRUD handlers for entity (type / size)
     */
    function initEntityHandlers(entity, endpoint) {
        const modal = $(`#${entity}sModal`);
        const formModal = $(`#${entity}FormModal`);
        const form = $(`#${entity}Form`);
        const list = $(`#${entity}sList`);
        const addBtn = $(`#add${capitalize(entity)}Btn`);
        const saveBtn = $(`#save${capitalize(entity)}Btn`);
        const modalLabel = $(`#${entity}FormModalLabel`);

        // Load items when modal is shown
        modal.on('show.bs.modal', function () {
            loadItems();
        });

        // Add button
        addBtn.on('click', function () {
            form[0].reset();
            $(`#${entity}Id`).val('');
            modalLabel.text(`Add ${capitalize(entity)}`);
            formModal.modal('show');
        });

        // Save button
        saveBtn.on('click', function () {
            const id = $(`#${entity}Id`).val();
            const formData = new FormData(form[0]);
            let url = endpoint;
            let method = 'POST';

            if (id) {
                url = `${endpoint}/${id}`;
                formData.append('_method', 'PUT'); // Laravel expects this
            }

            $.ajax({
                url: url,
                method: 'POST', // always POST when sending FormData
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.success) {
                        formModal.modal('hide');
                        loadItems();
                        showAlert('success', data.message);
                    } else {
                        showAlert('error', data.message);
                    }
                },
                error: function () {
                    showAlert('error', 'An error occurred');
                }
            });
        });

        // Load items
        function loadItems() {
            $.get(endpoint, function (items) {
                list.empty();
                if (items.length === 0) {
                    list.html(`<p class="text-muted">No ${entity}s found</p>`);
                    return;
                }

                $.each(items, function (index, item) {
                    const row = $(`
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <span>${item.name}</span>
                            <div>
                                <button class="btn btn-sm btn-warning me-1 edit-${entity}" data-id="${item.id}" data-name="${item.name}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-${entity}" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                    list.append(row);
                });
            });
        }

        // Edit item
        list.on('click', `.edit-${entity}`, function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $(`#${entity}Id`).val(id);
            $(`#${entity}Name`).val(name);
            modalLabel.text(`Edit ${capitalize(entity)}`);
            formModal.modal('show');
        });

        // Delete item
        list.on('click', `.delete-${entity}`, function () {
            const id = $(this).data('id');
            if (confirm(`Are you sure you want to delete this ${entity}?`)) {
                $.ajax({
                    url: `${endpoint}/${id}`,
                    method: 'DELETE',
                    success: function (data) {
                        if (data.success) {
                            loadItems();
                            showAlert('success', data.message);
                        } else {
                            showAlert('error', data.message);
                        }
                    },
                    error: function () {
                        showAlert('error', 'An error occurred');
                    }
                });
            }
        });
    }

    /**
     * Helper: Show alert
     */
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show mt-2" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        $('.container').prepend(alert);
        setTimeout(() => alert.alert('close'), 4000);
    }

    /**
     * Helper: Capitalize string
     */
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
</script>

@endpush