@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Create New Role') }}</h2>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Roles') }}
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }} * <small class="text-muted">({{ __('For code use') }})</small></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required 
                                           placeholder="e.g., admin, manager">
                                    <div class="form-text">{{ __('Lowercase, use hyphens or underscores. Example: super-admin') }}</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }} * <small class="text-muted">({{ __('For UI display') }})</small></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required
                                           placeholder="e.g., Super Admin, Manager">
                                    <div class="form-text">{{ __('Display name shown in the UI') }}</div>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('permissions.Permissions') }}</label>
                            <div class="border rounded p-3" style="max-height: 500px; overflow-y: auto;">
                                @foreach($permissions as $group => $groupPermissions)
                                    <div class="mb-4">
                                        <h6 class="text-primary mb-2">
                                            <strong>{{ ucfirst($group) }}</strong>
                                            <button type="button" class="btn btn-sm btn-link p-0 ms-2 select-all-group" data-group="{{ $group }}">
                                                {{ __('Select All') }}
                                            </button>
                                        </h6>
                                        <div class="row">
                                            @foreach($groupPermissions as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}" 
                                                               id="permission_{{ $permission->id }}"
                                                               data-group="{{ $group }}">
                                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                            {{ __("permissions.{$permission->name}", [], app()->getLocale()) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('Create Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all permissions in a group
        document.querySelectorAll('.select-all-group').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.getAttribute('data-group');
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });
            });
        });
    });
</script>
@endpush
@endsection

