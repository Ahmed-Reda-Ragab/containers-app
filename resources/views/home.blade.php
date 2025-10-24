@extends('layouts.master')

@section('content')
<div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $dashboardData['todays_summary']['scheduled_for_unloading'] }}</h4>
                            <p class="mb-0">{{ __('containers.scheduled_for_unloading_today') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $dashboardData['todays_summary']['actually_unloaded'] }}</h4>
                            <p class="mb-0">{{ __('containers.actually_unloaded_today') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $dashboardData['todays_summary']['available_containers'] }}</h4>
                            <p class="mb-0">{{ __('containers.available_containers') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white clickable-card" data-bs-toggle="modal" data-bs-target="#unavailableContainersModal">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $dashboardData['todays_summary']['unavailable_containers'] }}</h4>
                            <p class="mb-0">{{ __('containers.unavailable_containers') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Container Status Distribution (Donut Chart) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('containers.container_status_distribution') }}</h5>
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
                    <h5 class="mb-0">{{ __('containers.container_activity_by_month') }}</h5>
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
                    <h5 class="mb-0">{{ __('containers.daily_unloading_trends') }}</h5>
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
                    <h5 class="mb-0">{{ __('containers.top_5_clients') }}</h5>
                </div>
                <div class="card-body">
                    @if(count($dashboardData['top_clients']) > 0)
                        @foreach($dashboardData['top_clients'] as $index => $client)
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
                            @foreach($dashboardData['unavailable_containers'] as $container)
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
.clickable-card {
    cursor: pointer;
    transition: transform 0.2s;
}

.clickable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    // Container Status Distribution (Donut Chart)
    const statusCtx = document.getElementById('containerStatusChart').getContext('2d');
    const statusData = @json($dashboardData['container_status_distribution']);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.label),
            datasets: [{
                data: statusData.map(item => item.value),
                backgroundColor: [
                    '#28a745', // Available - Green
                    '#ffc107', // In Use - Yellow
                    '#dc3545', // Maintenance - Red
                    '#6c757d'  // Other - Gray
                ],
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

    // Monthly Activity (Bar Chart)
    const monthlyCtx = document.getElementById('monthlyActivityChart').getContext('2d');
    const monthlyData = @json($dashboardData['monthly_activity']);
    
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

    // Daily Trends (Line Chart)
    const trendsCtx = document.getElementById('dailyTrendsChart').getContext('2d');
    const trendsData = @json($dashboardData['daily_trends']);
    
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
});

// Navigate to client function
function navigateToClient(clientId) {
    // You can implement navigation to client details page
    window.location.href = `/customers/${clientId}`;
}
</script>
@endsection
