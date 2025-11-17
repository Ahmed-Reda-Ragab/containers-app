@extends('layouts.master')

@section('content')
@php
    $topClients = data_get($dashboardData, 'top_clients', []);
    $unavailableContainers = data_get($dashboardData, 'unavailable_containers', []);
@endphp

<div class="container-fluid">
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ __('Company Contracts') }}</h5>
                            <p class="text-muted mb-0">{{ __('Business segment status') }}</p>
                        </div>
                        <i class="fas fa-file-contract text-primary fa-2x"></i>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <small class="text-muted">{{ __('Active') }}</small>
                            <h3 class="mb-0">{{ data_get($dashboardData, 'contract_stats.active_contracts', 0) }}</h3>
                        </div>
                        <div>
                            <small class="text-muted">{{ __('Near Expiry (15 days)') }}</small>
                            <h3 class="mb-0 text-warning">{{ data_get($dashboardData, 'contract_stats.near_expiry_contracts', 0) }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('contracts.index.by-type', ['type' => 'business']) }}" class="btn btn-sm btn-primary mt-3">
                        {{ __('View contracts') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ __('Company Quotations') }}</h5>
                            <p class="text-muted mb-0">{{ __('Quotation pipeline health') }}</p>
                        </div>
                        <i class="fas fa-file-signature text-info fa-2x"></i>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <small class="text-muted">{{ __('Active') }}</small>
                            <h3 class="mb-0">{{ data_get($dashboardData, 'contract_stats.active_quotations', 0) }}</h3>
                        </div>
                        <div>
                            <small class="text-muted">{{ __('Expired') }}</small>
                            <h3 class="mb-0 text-danger">{{ data_get($dashboardData, 'contract_stats.expired_quotations', 0) }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('offers.index') }}" class="btn btn-sm btn-primary mt-3">
                        {{ __('View quotations') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ __('Welcome to Container Management System') }}</h4>
                    <p class="card-text">{{ __('You are logged in! Start managing your containers.') }}</p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('containers.index') }}" class="btn btn-primary">
                            <i class="fas fa-box-open"></i> {{ __('Manage Containers') }}
                        </a>
                        <a href="{{ route('containers.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> {{ __('Add New Container') }}
                        </a>
                        <a href="{{ route('containers.create-bulk') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> {{ __('containers.bulk_create_containers') }}
                        </a>
                        <a href="{{ route('contracts.index') }}" class="btn btn-info">
                            <i class="fas fa-file-contract"></i> {{ __('Manage Contracts') }}
                        </a>

                        <a href="{{ route('contracts.create.by-type', ['type' => 'business']) }}" class="btn btn-primary">{{ __('New Business Contract') }}</a>
                        <a href="{{ route('contracts.create.by-type', ['type' => 'individual']) }}" class="btn btn-primary">{{ __('New Individual Contract') }}</a>

                         <a href="{{ route('contracts.quick.individual') }}" class="btn btn-info"><i class="fas fa-file-contract"></i> {{ __('New Individual Contract') }}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="{{route('contract-container-filled',['expected_discharge_date' => date('Y-m-d')] ) }}" class="card-link card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ data_get($dashboardData, 'todays_summary.scheduled_for_unloading', 0) }}</h4>
                            <p class="mb-0">{{ __('containers.scheduled_for_unloading_today') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="{{route('contract-container-filled',['discharge_date' => date('Y-m-d')] ) }}" class="card-link card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ data_get($dashboardData, 'todays_summary.actually_unloaded', 0) }}</h4>
                            <p class="mb-0">{{ __('containers.actually_unloaded_today') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="{{route('containers.index',['status' =>  App\Enums\ContainerStatus::AVAILABLE->value ] ) }}" class="card-link card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ data_get($dashboardData, 'todays_summary.available_containers', 0) }}</h4>
                            <p class="mb-0">{{ __('containers.available_containers') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{route('containers.index',['status' =>  App\Enums\ContainerStatus::IN_USE->value ] ) }}" class="card-link card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ data_get($dashboardData, 'todays_summary.unavailable_containers', 0) }}</h4>
                            <p class="mb-0">{{ __('containers.unavailable_containers') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </a>
            <button class="btn btn-outline-warning text-dark w-100 mt-2" data-bs-toggle="modal" data-bs-target="#unavailableContainersModal">
                {{ __('View unavailable list') }}
            </button>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Container Status Distribution (Donut Chart) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">{{ __('containers.container_status_distribution') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="containerStatusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Activity (Bar Chart) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">

                        <h5 class="mb-0">{{ __('containers.container_activity_by_month') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyActivityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Trends and Top Clients -->
    <div class="row mb-4">
        <!-- Daily Unloading Trends (Line Chart) -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">

                        <h5 class="mb-0">{{ __('containers.daily_unloading_trends') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="dailyTrendsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 Clients -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">{{ __('containers.top_5_clients') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($topClients) > 0)
                    @foreach($topClients as $index => $client)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded clickable-item"
                        onclick="navigateToClient({{ $client['id'] }})">
                        <div>
                            <h6 class="mb-1">{{ $client['name'] }}</h6>
                            <small class="text-muted">{{ $client['company_name'] }}</small>
                            <br>
                            <small class="text-info">{{ $client['total_fills'] }} fills</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $index + 1 }}</span>
                            <br>
                            <small class="text-muted">{{ $client['most_active_month'] }}</small>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted">{{ __('containers.no_client_data_available') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unavailable Containers Modal -->
<div class="modal fade" id="unavailableContainersModal" tabindex="-1" aria-labelledby="unavailableContainersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unavailableContainersModalLabel">{{ __('containers.unavailable_containers') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Size') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unavailableContainers as $container)
                            <tr>
                                <td><strong>{{ $container['code'] }}</strong></td>
                                <td><span class="badge bg-info">{{ $container['size'] }}</span></td>
                                <td><span class="badge bg-{{ $container['status'] === 'in_use' ? 'warning' : 'danger' }}">{{ $container['status_label'] }}</span></td>
                                <td>{{ $container['description'] ?: __('No description') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <a href="{{ route('containers.index') }}" class="btn btn-primary">{{ __('containers.manage_containers') }}</a>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .card-link {
        display: block;
        text-decoration: none;
        border-radius: 0.65rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: inherit;
    }

    .clickable-item {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .clickable-item:hover {
        background-color: #f8f9fa;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusCanvas = document.getElementById('containerStatusChart');
        const statusData = @json(data_get($dashboardData, 'container_status_distribution', []));
        if (statusCanvas && statusData.length) {
            const statusCtx = statusCanvas.getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(item => item.label),
                    datasets: [{
                        data: statusData.map(item => item.value),
                        backgroundColor: statusData.map(item => {
                            switch (item.status) {
                                case 'available': return '#28a745';
                                case 'in_use': return '#ffc107';
                                case 'filled': return '#fd7e14';
                                case 'maintenance': return '#dc3545';
                                default: return '#6c757d';
                            }
                        }),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        const monthlyCanvas = document.getElementById('monthlyActivityChart');
        const monthlyData = @json(data_get($dashboardData, 'monthly_activity', []));
        if (monthlyCanvas && monthlyData.length) {
            const monthlyCtx = monthlyCanvas.getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: '{{ __("containers.activity_count") }}',
                        data: monthlyData.map(item => item.activity),
                        backgroundColor: '#007bff',
                        borderColor: '#0056b3',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const trendsCanvas = document.getElementById('dailyTrendsChart');
        const trendsData = @json(data_get($dashboardData, 'daily_trends', []));
        const loadsData = @json(data_get($dashboardData, 'daily_loads', []));
        if (trendsCanvas && trendsData.length) {
            const trendsCtx = trendsCanvas.getContext('2d');
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendsData.map(item => item.date),
                    datasets: [{
                        label: '{{ __("containers.daily_unloads") }}',
                        data: trendsData.map(item => item.count),
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },{
                        label: '{{ __("containers.daily_loads") }}',
                        data: loadsData.map(item => item.count),
                        borderColor: '#1b84ff',
                        backgroundColor: 'rgba(255, 8, 8, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });

    // Navigate to client function
    function navigateToClient(clientId) {
        // You can implement navigation to client details page
        window.location.href = `/customers/${clientId}`;
    }
</script>
@endsection