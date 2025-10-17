@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Container Fill Details') }} #{{ $contractContainerFill->no }}</h2>
                <div class="btn-group">
                    <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Contract') }}
                    </a>
                    <a href="{{ route('contract-container-fills.edit', $contractContainerFill) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Container Fill Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Container Number') }}:</th>
                                            <td><strong>#{{ $contractContainerFill->no }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Contract') }}:</th>
                                            <td>
                                                <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="text-decoration-none">
                                                    #{{ $contractContainerFill->contract->id }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Container') }}:</th>
                                            <td>{{ $contractContainerFill->container->code ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Container Type') }}:</th>
                                            <td>{{ $contractContainerFill->container->type->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Delivered By') }}:</th>
                                            <td>{{ $contractContainerFill->deliver->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Delivery Date') }}:</th>
                                            <td>{{ $contractContainerFill->deliver_at->format('Y-m-d') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Expected Discharge') }}:</th>
                                            <td>{{ $contractContainerFill->expected_discharge_date->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Discharge Date') }}:</th>
                                            <td>{{ $contractContainerFill->discharge_date ? $contractContainerFill->discharge_date->format('Y-m-d') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Discharged By') }}:</th>
                                            <td>{{ $contractContainerFill->discharge->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Client') }}:</th>
                                            <td>{{ $contractContainerFill->client->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('City') }}:</th>
                                            <td>{{ $contractContainerFill->city }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Address') }}:</th>
                                            <td>{{ $contractContainerFill->address }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($contractContainerFill->price)
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <strong>{{ __('Price') }}:</strong> {{ number_format($contractContainerFill->price, 2) }} {{ __('SAR') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($contractContainerFill->notes)
                                <div class="mt-4">
                                    <h6>{{ __('Notes') }}</h6>
                                    <div class="alert alert-light">
                                        {{ $contractContainerFill->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Status') }}</h6>
                        </div>
                        <div class="card-body text-center">
                            @if($contractContainerFill->is_discharged)
                                <div class="mb-3">
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                </div>
                                <h5 class="text-success">{{ __('Discharged') }}</h5>
                                <p class="text-muted">{{ __('Container has been discharged') }}</p>
                            @elseif($contractContainerFill->is_overdue)
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                                </div>
                                <h5 class="text-danger">{{ __('Overdue') }}</h5>
                                <p class="text-muted">{{ __('Container is overdue for discharge') }}</p>
                            @else
                                <div class="mb-3">
                                    <i class="fas fa-clock fa-3x text-warning"></i>
                                </div>
                                <h5 class="text-warning">{{ __('Active') }}</h5>
                                <p class="text-muted">{{ __('Container is currently active') }}</p>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted">
                                    {{ __('Expected Discharge') }}: {{ $contractContainerFill->expected_discharge_date->format('Y-m-d') }}
                                </small>
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
                                <a href="{{ route('contract-container-fills.edit', $contractContainerFill) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> {{ __('Edit Container Fill') }}
                                </a>
                                <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="btn btn-info">
                                    <i class="fas fa-file-contract"></i> {{ __('View Contract') }}
                                </a>
                                @if(!$contractContainerFill->is_discharged)
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dischargeModal">
                                        <i class="fas fa-check"></i> {{ __('Discharge Container') }}
                                    </button>
                                @endif
                                <form action="{{ route('contract-container-fills.destroy', $contractContainerFill) }}" method="POST" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this container fill?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> {{ __('Delete Container Fill') }}
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

<!-- Discharge Modal -->
@if(!$contractContainerFill->is_discharged)
    <div class="modal fade" id="dischargeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('contract-container-fills.discharge', $contractContainerFill) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Discharge Container') }} #{{ $contractContainerFill->no }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="discharge_date" class="form-label">{{ __('Discharge Date') }} *</label>
                            <input type="date" class="form-control" id="discharge_date" name="discharge_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="discharge_id" class="form-label">{{ __('Discharged By') }} *</label>
                            <select class="form-select" id="discharge_id" name="discharge_id" required>
                                <option value="">{{ __('Choose user...') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Discharge Container') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Set default discharge date to today
    $('#discharge_date').val(new Date().toISOString().split('T')[0]);
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection


