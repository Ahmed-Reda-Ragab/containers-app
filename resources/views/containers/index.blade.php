@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Containers Management') }}</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#typesModal">
                            <i class="fas fa-cog"></i> {{ __('Manage Types') }}
                        </button>
                        <a href="{{ route('containers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('Add New Container') }}
                        </a>
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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Code') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($containers as $container)
                                        <tr>
                                            <td>{{ $container->id }}</td>
                                            <td>
                                                <strong>{{ $container->code }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $container->type->name??'' }}</span>
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
                                                    <a href="{{ route('containers.show', $container) }}" class="btn btn-sm btn-outline-info" title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('containers.edit', $container) }}" class="btn btn-sm btn-outline-warning" title="{{ __('Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('containers.destroy', $container) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this container?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $containers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No containers found') }}</h5>
                            <p class="text-muted">{{ __('Get started by creating your first container.') }}</p>
                            <a href="{{ route('containers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Add New Container') }}
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

<!-- Types Management Modal -->
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

<!-- Add/Edit Type Modal -->
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
                    <input type="hidden" id="typeId" name="type_id">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typesModal = document.getElementById('typesModal');
    const typeFormModal = document.getElementById('typeFormModal');
    const addTypeBtn = document.getElementById('addTypeBtn');
    const saveTypeBtn = document.getElementById('saveTypeBtn');
    const typeForm = document.getElementById('typeForm');
    const typesList = document.getElementById('typesList');
    const typeFormModalLabel = document.getElementById('typeFormModalLabel');

    // Load types when modal opens
    typesModal.addEventListener('show.bs.modal', function() {
        loadTypes();
    });

    // Add type button
    addTypeBtn.addEventListener('click', function() {
        typeForm.reset();
        document.getElementById('typeId').value = '';
        typeFormModalLabel.textContent = '{{ __("Add Type") }}';
        new bootstrap.Modal(typeFormModal).show();
    });

    // Save type
    saveTypeBtn.addEventListener('click', function() {
        const formData = new FormData(typeForm);
        const typeId = document.getElementById('typeId').value;
        const url = typeId ? `/types/${typeId}` : '/types';
        const method = typeId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(typeFormModal).hide();
                loadTypes();
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'An error occurred');
        });
    });

    // Load types
    function loadTypes() {
        fetch('/types')
        .then(response => response.json())
        .then(types => {
            typesList.innerHTML = '';
            if (types.length === 0) {
                typesList.innerHTML = '<p class="text-muted">{{ __("No types found") }}</p>';
                return;
            }
            
            types.forEach(type => {
                const typeItem = document.createElement('div');
                typeItem.className = 'd-flex justify-content-between align-items-center border rounded p-2 mb-2';
                typeItem.innerHTML = `
                    <span>${type.name}</span>
                    <div>
                        <button class="btn btn-sm btn-warning me-1" onclick="editType(${type.id}, '${type.name}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteType(${type.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                typesList.appendChild(typeItem);
            });
        });
    }

    // Edit type
    window.editType = function(id, name) {
        document.getElementById('typeId').value = id;
        document.getElementById('typeName').value = name;
        typeFormModalLabel.textContent = '{{ __("Edit Type") }}';
        new bootstrap.Modal(typeFormModal).show();
    };

    // Delete type
    window.deleteType = function(id) {
        if (confirm('{{ __("Are you sure you want to delete this type?") }}')) {
            fetch(`/types/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadTypes();
                    showAlert('success', data.message);
                } else {
                    showAlert('error', data.message);
                }
            });
        }
    };

    // Show alert
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container').insertBefore(alert, document.querySelector('.container').firstChild);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
});
</script>
@endsection
