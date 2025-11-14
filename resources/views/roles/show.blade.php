@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Role Details') }}: {{ $role->title ?? $role->name }}</h2>
                <div>
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Roles') }}
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Name') }}:</strong>
                            <code>{{ $role->name }}</code>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Title') }}:</strong>
                            {{ $role->title ?? $role->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Created At') }}:</strong>
                            {{ $role->created_at->format('Y-m-d H:i:s') }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Updated At') }}:</strong>
                            {{ $role->updated_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">{{ __('permissions.Permissions') }} ({{ $role->permissions->count() }})</h5>
                    
                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @foreach($role->permissions->groupBy(function($permission) {
                                $parts = explode('.', $permission->name);
                                return $parts[0] ?? 'other';
                            }) as $group => $groupPermissions)
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary">{{ ucfirst($group) }}</h6>
                                    <ul class="list-unstyled">
                                        @foreach($groupPermissions as $permission)
                                            <li>
                                                <i class="fas fa-check text-success"></i>
                                                {{ __("permissions.{$permission->name}", [], app()->getLocale()) }}
                                                <code class="small text-muted">({{ $permission->name }})</code>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ __('No permissions assigned to this role.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

