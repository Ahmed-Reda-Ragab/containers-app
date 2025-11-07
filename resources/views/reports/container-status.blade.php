@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Container Status Report') }}</h2>
                <div>
                    <a href="{{ route('reports.container-status.print') }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> {{ __('Print Report') }}
                    </a>
                    <a href="{{ route('containers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Containers') }}
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $containerStats['total'] }}</h5>
                            <p class="card-text">{{ __('Total Containers') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $containerStats['available'] }}</h5>
                            <p class="card-text">{{ __('Available') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ $containerStats['in_use'] }}</h5>
                            <p class="card-text">{{ __('In Use') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">{{ $containerStats['filled'] }}</h5>
                            <p class="card-text">{{ __('Filled') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">{{ $containerStats['maintenance'] }}</h5>
                            <p class="card-text">{{ __('Maintenance') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-danger">{{ $containerStats['out_of_service'] }}</h5>
                            <p class="card-text">{{ __('Out of Service') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Container Status Table -->
            <div class="card">
                <div class="card-header">
                <div class="card-title">

                    <h5 class="mb-0">{{ __('Container Status Details') }}</h5>
                </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('SN') }}</th>
                                    <th>{{ __('Container Code') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Location') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($containers as $index => $container)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $container->code }}</strong></td>
                                    <td>{{ $container->size->name ?? '-' }}</td>
                                    <td>{{ $container->size ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $container->status->color() }}">
                                            {{ $container->status->label() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($container->status->value === 'available')
                                            <span class="text-success">{{ __('Warehouse') }}</span>
                                        @elseif($container->status->value === 'in_use')
                                            <span class="text-info">{{ __('At Customer') }}</span>
                                        @elseif($container->status->value === 'filled')
                                            <span class="text-warning">{{ __('Ready for Pickup') }}</span>
                                        @else
                                            <span class="text-secondary">{{ __('Service Center') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $container->description ?? '-' }}</td>
                                    <td>
                                        @if($container->status->value === 'available')
                                            <span class="text-success">{{ __('Ready') }}</span>
                                        @else
                                            <span class="text-info">{{ __('Active') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table th {
    background-color: #376092 !important;
    color: white !important;
    font-weight: bold;
}

.table td {
    vertical-align: middle;
}

.card-body h5 {
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
@endpush
@endsection


