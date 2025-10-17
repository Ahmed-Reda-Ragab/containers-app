@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Contract Details') }} #{{ $contract->id }}</h2>
                <div class="btn-group">
                    <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Contracts') }}
                    </a>
                    <a href="{{ route('contracts.print', $contract) }}" class="btn btn-warning" target="_blank">
                        <i class="fas fa-print"></i> {{ __('Print Contract') }}
                    </a>
                    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('payments.create-for-contract', $contract) }}" class="btn btn-success">
                        <i class="fas fa-money-bill"></i> {{ __('Add Payment') }}
                    </a>
                    <a href="{{ route('contract-container-fills.create-for-contract', $contract) }}" class="btn btn-info">
                        <i class="fas fa-truck"></i> {{ __('Add Container Fill') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contract Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Contract Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('Contract #') }}:</th>
                                    <td><strong>#{{ $contract->id }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Customer') }}:</th>
                                    <td>{{ $contract->customer['name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Contact Person') }}:</th>
                                    <td>{{ $contract->customer['contact_person'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Telephone') }}:</th>
                                    <td>{{ $contract->customer['telephone'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Mobile') }}:</th>
                                    <td>{{ $contract->customer['mobile'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('City') }}:</th>
                                    <td>{{ $contract->customer['city'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Address') }}:</th>
                                    <td>{{ $contract->customer['address'] ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('Container Type') }}:</th>
                                    <td>{{ $contract->type->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Container Price') }}:</th>
                                    <td><strong>{{ number_format($contract->container_price, 2) }} {{ __('SAR') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Number of Containers') }}:</th>
                                    <td>{{ $contract->no_containers }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Contract Period') }}:</th>
                                    <td>{{ $contract->contract_period }} {{ __('days') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Start Date') }}:</th>
                                    <td>{{ $contract->start_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('End Date') }}:</th>
                                    <td>{{ $contract->end_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Status') }}:</th>
                                    <td>
                                        <span class="badge bg-{{ $contract->status_badge }}">
                                            {{ ucfirst($contract->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Financial Summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-primary">{{ number_format($contract->monthly_total_dumping_cost, 2) }} {{ __('SAR') }}</div>
                                <small class="text-muted">{{ __('Monthly Total Dumping Cost') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-info">{{ number_format($contract->additional_trip_cost, 2) }} {{ __('SAR') }}</div>
                                <small class="text-muted">{{ __('Additional Trip Cost') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-warning">{{ number_format($contract->tax_value, 2) }}%</div>
                                <small class="text-muted">{{ __('Tax Value') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-success">{{ number_format($contract->total_price, 2) }} {{ __('SAR') }}</div>
                                <small class="text-muted">{{ __('Total Price') }}</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Total Price') }}:</span>
                                <strong>{{ number_format($contract->total_price, 2) }} {{ __('SAR') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Total Paid') }}:</span>
                                <strong class="text-success">{{ number_format($contract->total_payed, 2) }} {{ __('SAR') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Remaining Amount') }}:</span>
                                <strong class="text-danger">{{ number_format($contract->remaining_amount, 2) }} {{ __('SAR') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $contract->total_price > 0 ? ($contract->total_payed / $contract->total_price) * 100 : 0 }}%"
                                     aria-valuenow="{{ $contract->total_price > 0 ? ($contract->total_payed / $contract->total_price) * 100 : 0 }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    {{ $contract->total_price > 0 ? round(($contract->total_payed / $contract->total_price) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for detailed information -->
            <ul class="nav nav-tabs" id="contractTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                        {{ __('Payments') }} ({{ $contract->payments->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="container-fills-tab" data-bs-toggle="tab" data-bs-target="#container-fills" type="button" role="tab">
                        {{ __('Container Fills') }} ({{ $contract->contractContainerFills->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">
                        {{ __('Terms & Conditions') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="contractTabsContent">
                <!-- Payments Tab -->
                <div class="tab-pane fade show active" id="payments" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            @if($contract->payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Recorded By') }}</th>
                                                <th>{{ __('Notes') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contract->payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                                    <td><strong>{{ number_format($payment->payed, 2) }} {{ __('SAR') }}</strong></td>
                                                    <td>{{ $payment->method ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $payment->is_payed ? 'success' : 'warning' }}">
                                                            {{ $payment->is_payed ? __('Paid') : __('Pending') }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $payment->user->name }}</td>
                                                    <td>{{ $payment->notes ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline"
                                                                  onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('No payments recorded yet.') }}</p>
                                    <a href="{{ route('payments.create-for-contract', $contract) }}" class="btn btn-success">
                                        {{ __('Record First Payment') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Container Fills Tab -->
                <div class="tab-pane fade" id="container-fills" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            @if($contract->contractContainerFills->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Container #') }}</th>
                                                <th>{{ __('Container') }}</th>
                                                <th>{{ __('Delivered By') }}</th>
                                                <th>{{ __('Delivery Date') }}</th>
                                                <th>{{ __('Expected Discharge') }}</th>
                                                <th>{{ __('Discharge Date') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contract->contractContainerFills as $fill)
                                                <tr>
                                                    <td><strong>#{{ $fill->no }}</strong></td>
                                                    <td>{{ $fill->container->code ?? 'N/A' }}</td>
                                                    <td>{{ $fill->deliver->name ?? 'N/A' }}</td>
                                                    <td>{{ $fill->deliver_at->format('Y-m-d') }}</td>
                                                    <td>{{ $fill->expected_discharge_date->format('Y-m-d') }}</td>
                                                    <td>{{ $fill->discharge_date ? $fill->discharge_date->format('Y-m-d') : 'N/A' }}</td>
                                                    <td>
                                                        @if($fill->is_discharged)
                                                            <span class="badge bg-success">{{ __('Discharged') }}</span>
                                                        @elseif($fill->is_overdue)
                                                            <span class="badge bg-danger">{{ __('Overdue') }}</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ __('Active') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                        @if(!$fill->is_discharged)
                                                 
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#dischargeModal"
                                                            data-url="{{ route('contract-container-fills.discharge', $fill) }}"
                                                            >
                                                            <i class="fas fa-dolly"></i> {{ __('Discharge') }}
                                                            </button>

                                                                                                        @endif


                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('No container fills recorded yet.') }}</p>
                                    <a href="{{ route('contract-container-fills.create-for-contract', $contract) }}" class="btn btn-info">
                                        {{ __('Record First Container Fill') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Terms Tab -->
                <div class="tab-pane fade" id="terms" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>{{ __('Agreement Terms') }}</h6>
                                    <p class="text-muted">{{ $contract->agreement_terms ?? __('No terms specified.') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __('Material Restrictions') }}</h6>
                                    <p class="text-muted">{{ $contract->material_restrictions ?? __('No restrictions specified.') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>{{ __('Delivery Terms') }}</h6>
                                    <p class="text-muted">{{ $contract->delivery_terms ?? __('No delivery terms specified.') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __('Payment Policy') }}</h6>
                                    <p class="text-muted">{{ $contract->payment_policy ?? __('No payment policy specified.') }}</p>
                                </div>
                            </div>
                            @if($contract->notes)
                                <div class="row">
                                    <div class="col-12">
                                        <h6>{{ __('Notes') }}</h6>
                                        <p class="text-muted">{{ $contract->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discharge Modal for each container fill -->

<!-- Replace Container Modals -->
@if(isset($contract->contractContainerFills) && $contract->contractContainerFills->count() > 0)
    @foreach($contract->contractContainerFills as $fill)
        @if($fill->container->status === 'filled' && !$fill->is_discharged)
            <div class="modal fade" id="replaceModal{{ $fill->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Replace Container') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('contract-container-fills.replace', $fill) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="new_container_id{{ $fill->id }}" class="form-label">{{ __('New Container') }} *</label>
                                    <select class="form-select" id="new_container_id{{ $fill->id }}" name="new_container_id" required>
                                        <option value="">{{ __('Choose a container...') }}</option>
                                        @foreach($availableContainers as $container)
                                            <option value="{{ $container->id }}">
                                                {{ $container->code }} - {{ $container->type->name }} ({{ $container->size }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="replace_date{{ $fill->id }}" class="form-label">{{ __('Replace Date') }} *</label>
                                    <input type="date" class="form-control" id="replace_date{{ $fill->id }}" name="replace_date" 
                                           value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="replace_user_id{{ $fill->id }}" class="form-label">{{ __('Driver') }} *</label>
                                    <select class="form-select" id="replace_user_id{{ $fill->id }}" name="replace_user_id" required>
                                        <option value="">{{ __('Choose a driver...') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-info">{{ __('Replace Container') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
@include('components.discharge-modal')

@push('scripts')
<script>
$(document).ready(function() {
    // Set default discharge date to today
    $('input[name="discharge_date"]').val(new Date().toISOString().split('T')[0]);
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection
