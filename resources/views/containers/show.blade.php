@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Container Details: {{ $container->code }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('containers.edit', $container) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('containers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Container ID</label>
                            <p class="form-control-plaintext">{{ $container->id }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Container Code</label>
                            <p class="form-control-plaintext">
                                <strong class="text-primary">{{ $container->code }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Container Type') }}</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info fs-6">{{ $container->type->name??'' }}</span>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                @php
                                    $statusClass = match($container->status->value) {
                                        'available' => 'success',
                                        'in_use' => 'primary',
                                        'maintenance' => 'warning',
                                        'out_of_service' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} fs-6">{{ $container->status->label() }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <div class="form-control-plaintext">
                            @if($container->description)
                                {{ $container->description }}
                            @else
                                <span class="text-muted">No description provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext">{{ $container->created_at->format('M d, Y H:i:s') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <p class="form-control-plaintext">{{ $container->updated_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <form action="{{ route('containers.destroy', $container) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this container? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Container
                            </button>
                        </form>

                        <div class="btn-group">
                            <a href="{{ route('containers.edit', $container) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Container
                            </a>
                            <a href="{{ route('containers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> All Containers
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
