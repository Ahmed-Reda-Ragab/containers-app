@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Monthly Report') }}</h2>
                <div>
                    <a href="{{ route('reports.daily-report') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-day"></i> {{ __('Daily Report') }}
                    </a>
                    <a href="{{ route('reports.container-status') }}" class="btn btn-secondary">
                        <i class="fas fa-boxes"></i> {{ __('Container Status') }}
                    </a>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.monthly-report') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">{{ __('Year') }}</label>
                            <select class="form-select" id="year" name="year">
                                @for($i = now()->year - 2; $i <= now()->year + 1; $i++)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month" class="form-label">{{ __('Month') }}</label>
                            <select class="form-select" id="month" name="month">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
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

            <!-- Monthly Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $monthlyStats['days_in_month'] ?? 0 }}</h5>
                            <p class="card-text">{{ __('Days in Month') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $monthlyStats['deliveries'] ?? 0 }}</h5>
                            <p class="card-text">{{ __('Deliveries') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ $monthlyStats['discharges'] ?? 0 }}</h5>
                            <p class="card-text">{{ __('Discharges') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">{{ $monthlyStats['total_trips'] ?? 0 }}</h5>
                            <p class="card-text">{{ __('Total Trips') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Performance Metrics') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ __('Avg Daily Deliveries') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_daily_deliveries'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ __('Avg Daily Discharges') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_daily_discharges'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ __('Avg Days at Customer') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['avg_days_at_customer'] ?? 0 }} {{ __('days') }}
                                </div>
                            </div>
                            <div class="row mb-2">
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
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ __('Free Containers') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['free_containers'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ __('Active Contracts') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['active_contracts'] ?? 0 }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ __('Discharge Requests') }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $monthlyStats['discharge_requests'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipts Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Receipts Statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-warning">{{ $monthlyStats['receipts_issued'] ?? 0 }}</h5>
                                <p class="text-muted">{{ __('Receipts Issued') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-success">{{ $monthlyStats['receipts_collected'] ?? 0 }}</h5>
                                <p class="text-muted">{{ __('Receipts Collected') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-danger">{{ $monthlyStats['receipts_uncollected'] ?? 0 }}</h5>
                                <p class="text-muted">{{ __('Receipts Uncollected') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-info">{{ number_format($monthlyStats['receipts_amount_collected'] ?? 0, 2) }}</h5>
                                <p class="text-muted">{{ __('Amount Collected') }} ({{ __('SAR') }})</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Yearly Statistics') }} - {{ $yearlyStats['year'] ?? $year }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Year') }}</th>
                                    <th>{{ __('Total Deliveries') }}</th>
                                    <th>{{ __('Total Discharges') }}</th>
                                    <th>{{ __('Total Income') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>{{ $yearlyStats['year'] ?? $year }}</strong></td>
                                    <td><strong>{{ $yearlyStats['total_deliveries'] ?? 0 }}</strong></td>
                                    <td><strong>{{ $yearlyStats['total_discharges'] ?? 0 }}</strong></td>
                                    <td><strong>{{ number_format($yearlyStats['total_income'] ?? 0, 2) }} {{ __('SAR') }}</strong></td>
                                </tr>
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

.card-body h5 {
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
@endpush
@endsection

