@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        <h4 class="mb-0">{{ __('Filled Containers Management') }}</h4>
                    </div>
                    <div class="card-toolbar">

                        <div class="btn-group">
                            <a href="{{ route('containers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('Back to Containers') }}
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

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if($filledContainers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Contract') }}</th>
                                    <th>{{ __('Container') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Driver ID') }}</th>
                                    <th>{{ __('Filled Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filledContainers as $contractContainer)
                                <tr>
                                    <td>
                                        <strong>#{{ $contractContainer->contract->id }}</strong>
                                    </td>
                                    <td>
                                        @if($contractContainer->container)
                                        <strong>{{ $contractContainer->container->code }}</strong>
                                        @else
                                        <span class="text-muted">{{ __('Not Assigned') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $contractContainer->type->name }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $contractContainer->contract->customer->name }}</strong>
                                        @if($contractContainer->contract->customer->company_name)
                                        <br><small class="text-muted">{{ $contractContainer->contract->customer->company_name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contractContainer->contract->driver_id)
                                        <span class="badge bg-primary">{{ $contractContainer->contract->driver_id }}</span>
                                        @else
                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contractContainer->filled_at)
                                        {{ $contractContainer->filled_at->format('M d, Y H:i') }}
                                        @else
                                        <span class="text-muted">{{ __('Not Filled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $statusClass = match($contractContainer->status) {
                                        'assigned' => 'primary',
                                        'filled' => 'warning',
                                        'discharged' => 'success',
                                        default => 'secondary'
                                        };
                                        $statusText = match($contractContainer->status) {
                                        'assigned' => __('Assigned'),
                                        'filled' => __('Filled'),
                                        'discharged' => __('Discharged'),
                                        default => __('Unknown')
                                        };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($contractContainer->status === 'assigned' && !$contractContainer->container)
                                            <form action="{{ route('filled-containers.assign', $contractContainer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="{{ __('Assign Container') }}">
                                                    <i class="fas fa-plus"></i> {{ __('Assign') }}
                                                </button>
                                            </form>
                                            @endif

                                            @if($contractContainer->status === 'assigned' && $contractContainer->container)
                                            <form action="{{ route('filled-containers.mark-filled', $contractContainer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="{{ __('Mark as Filled') }}">
                                                    <i class="fas fa-exclamation-triangle"></i> {{ __('Mark Filled') }}
                                                </button>
                                            </form>
                                            @endif

                                            @if($contractContainer->status === 'filled')
                                            <form action="{{ route('filled-containers.discharge', $contractContainer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="{{ __('Discharge Container') }}">
                                                    <i class="fas fa-recycle"></i> {{ __('Discharge') }}
                                                </button>
                                            </form>
                                            @endif

                                            <a href="{{ route('contracts.show', $contractContainer->contract) }}" class="btn btn-sm btn-outline-info" title="{{ __('View Contract') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $filledContainers->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No filled containers found') }}</h5>
                        <p class="text-muted">{{ __('All containers are currently available or in use.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection