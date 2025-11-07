@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Receipts Management') }}</h2>
                <div>
                    
                    <a href="{{ route('reports.receipts-report') }}" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> {{ __('Receipts Report') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('receipts.index') }}" class="row g-3">
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

            <!-- Receipts Table -->
            <div class="card">
                <div class="card-header">
                <div class="card-title">

                    <h5 class="mb-0">{{ __('Receipts List') }}</h5>
                </div>
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
                                        <th>{{ __('Actions') }}</th>
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
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('receipts.show', $receipt) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('receipts.edit', $receipt) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- @if($receipt->status === 'issued')
                                                    <form action="{{ route('receipts.collect', $receipt) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm" title="{{ __('Mark as Collected') }}"
                                                                onclick="return confirm('{{ __('Mark this receipt as collected?') }}')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif -->
                                                <a href="{{ route('receipts.print', $receipt) }}" class="btn btn-outline-info btn-sm" target="_blank" title="{{ __('Print Receipt') }}">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
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
                            <!-- <a href="{{ route('receipts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Create New Receipt') }}
                            </a> -->
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


