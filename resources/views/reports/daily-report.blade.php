@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Daily Report') }}</h2>
                <div>
                    <a href="{{ route('reports.daily-report.print', ['date' => $selectedDate->format('Y-m-d')]) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> {{ __('Print Report') }}
                    </a>
                    <a href="{{ route('reports.monthly-report') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-bar"></i> {{ __('Monthly Report') }}
                    </a>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.daily-report') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">{{ __('Select Date') }}</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-search"></i> {{ __('Filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daily Statistics -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $dailyStats['deliveries'] }}</h5>
                            <p class="card-text">{{ __('Deliveries') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $dailyStats['discharges'] }}</h5>
                            <p class="card-text">{{ __('Discharges') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ $dailyStats['bookings'] }}</h5>
                            <p class="card-text">{{ __('Bookings') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">{{ $dailyStats['receipts_issued'] }}</h5>
                            <p class="card-text">{{ __('Receipts Issued') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">{{ $dailyStats['receipts_collected'] }}</h5>
                            <p class="card-text">{{ __('Receipts Collected') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-danger">{{ $dailyStats['deliveries'] + $dailyStats['discharges'] }}</h5>
                            <p class="card-text">{{ __('Total Trips') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Monthly Statistics') }} - {{ $selectedDate->format('F Y') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Year') }}</th>
                                    <th>{{ __('Month') }}</th>
                                    <th>{{ __('Deliveries') }}</th>
                                    <th>{{ __('Discharges') }}</th>
                                    <th>{{ __('Total Trips') }}</th>
                                    <th>{{ __('Total Income') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>{{ $monthlyStats['year'] }}</strong></td>
                                    <td><strong>{{ $monthlyStats['month'] }}</strong></td>
                                    <td><strong>{{ $monthlyStats['deliveries'] }}</strong></td>
                                    <td><strong>{{ $monthlyStats['discharges'] }}</strong></td>
                                    <td><strong>{{ $monthlyStats['total_trips'] }}</strong></td>
                                    <td><strong>{{ number_format($monthlyStats['total_income'] ?? 0, 2) }} {{ __('SAR') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Performance Metrics') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Avg Daily Deliveries') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_daily_deliveries'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Avg Daily Discharges') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_daily_discharges'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Avg Days at Customer') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_days_at_customer'] ?? 0 }} {{ __('days') }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Avg Delay Days') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_delay_days'] ?? 0 }} {{ __('days') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Container & Contract Status') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Free Containers') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['free_containers'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Active Contracts') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['active_contracts'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Receipts Issued') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['receipts_issued'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('Receipts Collected') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['receipts_collected'] ?? 0 }}
                                </div>
                            </div>
                        </div>
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

.card-body h5 {
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
@endpush
@endsection

