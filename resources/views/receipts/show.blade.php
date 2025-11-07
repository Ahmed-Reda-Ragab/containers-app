@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Receipt Details') }}</h2>
                <div>
                    <!-- <a href="{{ route('receipts.edit', $receipt) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a> -->
                    <a href="{{ route('receipts.print', $receipt) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-print"></i> {{ __('Print') }}
                    </a>
                    <a href="{{ route('receipts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Receipts') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Receipt Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Receipt Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Receipt Number') }}:</strong>
                                        <div class="h5 text-primary">{{ $receipt->receipt_number }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Status') }}:</strong>
                                        <div>
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Issue Date') }}:</strong>
                                        <div>{{ $receipt->issue_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Due Date') }}:</strong>
                                        <div class="{{ $receipt->is_overdue ? 'text-danger' : '' }}">
                                            {{ $receipt->due_date->format('d/m/Y') }}
                                            @if($receipt->is_overdue)
                                                <br><small class="text-danger">{{ __('Overdue') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Amount') }}:</strong>
                                        <div class="h5 text-success">{{ number_format($receipt->amount, 2) }} {{ __('SAR') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <!-- <strong>{{ __('Collection Date') }}:</strong>
                                        <div>
                                            @if($receipt->collection_date)
                                                {{ $receipt->collection_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            @if($receipt->notes)
                                <div class="mb-3">
                                    <strong>{{ __('Notes') }}:</strong>
                                    <div>{{ $receipt->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Customer Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Customer Name') }}:</strong>
                                        <div>{{ $receipt->customer_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Phone') }}:</strong>
                                        <div>{{ $receipt->customer_phone }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('Address') }}:</strong>
                                <div>{{ $receipt->customer_address }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('City') }}:</strong>
                                        <div>{{ $receipt->city }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Contract Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Contract ID') }}:</strong>
                                        <div>#{{ $receipt->contract_id }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Customer') }}:</strong>
                                        <div>{{ $receipt->customer->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Actions') }}</h6>
                        </div>
                        <div class="card-body">
                            <!-- @if($receipt->status === 'issued')
                                <form action="{{ route('receipts.collect', $receipt) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100"
                                            onclick="return confirm('{{ __('Mark this receipt as collected?') }}')">
                                        <i class="fas fa-check"></i> {{ __('Mark as Collected') }}
                                    </button>
                                </form>
                            @endif -->

                            <a href="{{ route('receipts.print', $receipt) }}" class="btn btn-info btn-sm w-100 mb-2" target="_blank">
                                <i class="fas fa-print"></i> {{ __('Print Receipt') }}
                            </a>

                            <a href="{{ route('receipts.edit', $receipt) }}" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-edit"></i> {{ __('Edit Receipt') }}
                            </a>
                        </div>
                    </div>

                    <!-- Staff Information -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Staff Information') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('Issued By') }}:</strong>
                                <div>{{ $receipt->issuedBy->name ?? '-' }}</div>
                            </div>
                            @if($receipt->collectedBy)
                                <div class="mb-3">
                                    <strong>{{ __('Collected By') }}:</strong>
                                    <div>{{ $receipt->collectedBy->name }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection


