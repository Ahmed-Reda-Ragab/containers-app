@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Payment Details') }}</h2>
                <div class="btn-group">
                    <a href="{{ route('contracts.show', $payment->contract) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Contract') }}
                    </a>
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Payment Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Payment ID') }}:</th>
                                            <td><strong>#{{ $payment->id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Amount') }}:</th>
                                            <td><strong class="text-success">{{ number_format($payment->payed, 2) }} {{ __('SAR') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Payment Method') }}:</th>
                                            <td>{{ $payment->method ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status') }}:</th>
                                            <td>
                                                <span class="badge bg-{{ $payment->is_payed ? 'success' : 'warning' }}">
                                                    {{ $payment->is_payed ? __('Paid') : __('Pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Recorded By') }}:</th>
                                            <td>{{ $payment->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Recorded At') }}:</th>
                                            <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Contract') }}:</th>
                                            <td>
                                                <a href="{{ route('contracts.show', $payment->contract) }}" class="text-decoration-none">
                                                    #{{ $payment->contract->id }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Customer') }}:</th>
                                            <td>{{ $payment->contract->customer['name'] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Contract Total') }}:</th>
                                            <td>{{ number_format($payment->contract->total_price, 2) }} {{ __('SAR') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Total Paid') }}:</th>
                                            <td>{{ number_format($payment->contract->total_payed, 2) }} {{ __('SAR') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Remaining') }}:</th>
                                            <td class="text-danger">{{ number_format($payment->contract->remaining_amount, 2) }} {{ __('SAR') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($payment->notes)
                                <div class="mt-4">
                                    <h6>{{ __('Notes') }}</h6>
                                    <div class="alert alert-light">
                                        {{ $payment->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Contract Progress -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Contract Progress') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="h4 text-primary">{{ number_format($payment->contract->total_price, 2) }} {{ __('SAR') }}</div>
                                <small class="text-muted">{{ __('Total Contract Value') }}</small>
                            </div>
                            
                            <div class="progress mb-3" style="height: 30px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $payment->contract->total_price > 0 ? ($payment->contract->total_payed / $payment->contract->total_price) * 100 : 0 }}%"
                                     aria-valuenow="{{ $payment->contract->total_price > 0 ? ($payment->contract->total_payed / $payment->contract->total_price) * 100 : 0 }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    {{ $payment->contract->total_price > 0 ? round(($payment->contract->total_payed / $payment->contract->total_price) * 100, 1) : 0 }}%
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Paid') }}:</span>
                                <strong class="text-success">{{ number_format($payment->contract->total_payed, 2) }} {{ __('SAR') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Remaining') }}:</span>
                                <strong class="text-danger">{{ number_format($payment->contract->remaining_amount, 2) }} {{ __('SAR') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Actions') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> {{ __('Edit Payment') }}
                                </a>
                                <a href="{{ route('contracts.show', $payment->contract) }}" class="btn btn-info">
                                    <i class="fas fa-file-contract"></i> {{ __('View Contract') }}
                                </a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this payment?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> {{ __('Delete Payment') }}
                                    </button>
                                </form>
                            </div>
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

