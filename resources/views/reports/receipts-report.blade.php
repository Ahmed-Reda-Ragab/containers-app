@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Receipts Report') }}</h2>
                <div>
                    <a href="{{ route('receipts.index') }}" class="btn btn-primary">
                        <i class="fas fa-receipt"></i> {{ __('Manage Receipts') }}
                    </a>
                    <a href="{{ route('reports.daily-report') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-bar"></i> {{ __('Daily Report') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.receipts-report') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                                <option value="issued" {{ $status === 'issued' ? 'selected' : '' }}>{{ __('Issued') }}</option>
                                <option value="collected" {{ $status === 'collected' ? 'selected' : '' }}>{{ __('Collected') }}</option>
                                <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">{{ __('From Date') }}</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">{{ __('To Date') }}</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-search"></i> {{ __('Filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">{{ $receiptStats['total_issued'] }}</h5>
                            <p class="card-text">{{ __('Issued') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $receiptStats['total_collected'] }}</h5>
                            <p class="card-text">{{ __('Collected') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-danger">{{ $receiptStats['total_overdue'] }}</h5>
                            <p class="card-text">{{ __('Overdue') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ number_format($receiptStats['total_amount_issued'], 2) }}</h5>
                            <p class="card-text">{{ __('Amount Issued') }} ({{ __('SAR') }})</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ number_format($receiptStats['total_amount_collected'], 2) }}</h5>
                            <p class="card-text">{{ __('Amount Collected') }} ({{ __('SAR') }})</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-danger">{{ number_format($receiptStats['total_amount_overdue'], 2) }}</h5>
                            <p class="card-text">{{ __('Amount Overdue') }} ({{ __('SAR') }})</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Rate -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Collection Performance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('Collection Rate') }}:</span>
                                <strong>
                                    @if($receiptStats['total_issued'] > 0)
                                        {{ number_format(($receiptStats['total_collected'] / $receiptStats['total_issued']) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </strong>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $receiptStats['total_issued'] > 0 ? ($receiptStats['total_collected'] / $receiptStats['total_issued']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('Overdue Rate') }}:</span>
                                <strong>
                                    @if($receiptStats['total_issued'] > 0)
                                        {{ number_format(($receiptStats['total_overdue'] / $receiptStats['total_issued']) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </strong>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ $receiptStats['total_issued'] > 0 ? ($receiptStats['total_overdue'] / $receiptStats['total_issued']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipts Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Receipts List') }}</h5>
                </div>
                <div class="card-body">
                    @if($receipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('Receipt #') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Issue Date') }}</th>
                                        <th>{{ __('Due Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Issued By') }}</th>
                                        <th>{{ __('Collection Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receipts as $receipt)
                                    <tr class="{{ $receipt->is_overdue ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ $receipt->receipt_number }}</strong><br>
                                            <small class="text-muted">#{{ $receipt->contract_id }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $receipt->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $receipt->customer_address }}</small>
                                        </td>
                                        <td>{{ $receipt->customer_phone }}</td>
                                        <td>
                                            <strong>{{ number_format($receipt->amount, 2) }} {{ __('SAR') }}</strong>
                                        </td>
                                        <td>{{ $receipt->issue_date->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $receipt->due_date->format('d/m/Y') }}
                                            @if($receipt->is_overdue)
                                                <br><small class="text-danger">{{ __('Overdue') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($receipt->status)
                                                @case('issued')
                                                    <span class="badge bg-warning">{{ __('Issued') }}</span>
                                                    @break
                                                @case('collected')
                                                    <span class="badge bg-success">{{ __('Collected') }}</span>
                                                    @break
                                                @case('overdue')
                                                    <span class="badge bg-danger">{{ __('Overdue') }}</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-secondary">{{ __('Cancelled') }}</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $receipt->issuedBy->name ?? '-' }}</td>
                                        <td>
                                            @if($receipt->collection_date)
                                                {{ $receipt->collection_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $receipts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No receipts found') }}</h5>
                            <p class="text-muted">{{ __('Try adjusting your filters or create a new receipt.') }}</p>
                            <a href="{{ route('receipts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Create New Receipt') }}
                            </a>
                        </div>
                    @endif
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

.table-warning {
    background-color: #fff3cd !important;
}
</style>
@endpush
@endsection

