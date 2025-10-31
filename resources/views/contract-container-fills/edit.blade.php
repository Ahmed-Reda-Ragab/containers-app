@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Edit Container Fill') }} #{{ $contractContainerFill->no }}</h2>
                <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Contract') }}
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contract-container-fills.update', $contractContainerFill) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Container Fill Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="contract_id" class="form-label">{{ __('Contract') }} *</label>
                                    <select class="form-select" id="contract_id" name="contract_id" required>
                                        <option value="">{{ __('Choose a contract...') }}</option>
                                        @foreach($contracts as $contractOption)
                                            <option value="{{ $contractOption->id }}" 
                                                    {{ $contractContainerFill->contract_id == $contractOption->id ? 'selected' : '' }}
                                                    data-customer="{{ $contractOption->customer['name'] ?? '' }}"
                                                    data-city="{{ $contractOption->customer['city'] ?? '' }}"
                                                    data-address="{{ $contractOption->customer['address'] ?? '' }}"
                                                    data-start-date="{{ $contractOption->start_date->format('Y-m-d') }}"
                                                    data-period="{{ $contractOption->contract_period }}">
                                                #{{ $contractOption->id }} - {{ $contractOption->customer['name'] ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="no" class="form-label">{{ __('Container Number') }} *</label>
                                            <input type="number" class="form-control" id="no" name="no" 
                                                   min="1" value="{{ $contractContainerFill->no }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="container_id" class="form-label">{{ __('Container') }} *</label>
                                            <select class="form-select" id="container_id" name="container_id" required>
                                                <option value="">{{ __('Choose a container...') }}</option>
                                                @foreach($containers as $container)
                                                    <option value="{{ $container->id }}" 
                                                            {{ $contractContainerFill->container_id == $container->id ? 'selected' : '' }}
                                                            data-code="{{ $container->code }}"
                                                            data-type="{{ $container->size->name ?? '' }}">
                                                        {{ $container->code }} - {{ $container->size->name ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deliver_id" class="form-label">{{ __('Delivered By') }} *</label>
                                            <select class="form-select" id="deliver_id" name="deliver_id" required>
                                                <option value="">{{ __('Choose user...') }}</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $contractContainerFill->deliver_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deliver_at" class="form-label">{{ __('Delivery Date') }} *</label>
                                            <input type="date" class="form-control" id="deliver_at" name="deliver_at" 
                                                   value="{{ $contractContainerFill->deliver_at->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expected_discharge_date" class="form-label">{{ __('Expected Discharge Date') }} *</label>
                                            <input type="date" class="form-control" id="expected_discharge_date" name="expected_discharge_date" 
                                                   value="{{ $contractContainerFill->expected_discharge_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">{{ __('Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="price" name="price" 
                                                       step="0.01" min="0" value="{{ $contractContainerFill->price }}">
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Leave empty to use contract price') }}</small>
                                        </div>
                                    </div>
                                </div>

                                @if($contractContainerFill->is_discharged)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discharge_date" class="form-label">{{ __('Discharge Date') }}</label>
                                                <input type="date" class="form-control" id="discharge_date" name="discharge_date" 
                                                       value="{{ $contractContainerFill->discharge_date ? $contractContainerFill->discharge_date->format('Y-m-d') : '' }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discharge_id" class="form-label">{{ __('Discharged By') }}</label>
                                                <select class="form-select" id="discharge_id" name="discharge_id" disabled>
                                                    <option value="">{{ __('Choose user...') }}</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ $contractContainerFill->discharge_id == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="client_id" class="form-label">{{ __('Client') }} *</label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value="">{{ __('Choose a client...') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $contractContainerFill->client_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">{{ __('City') }} *</label>
                                            <input type="text" class="form-control" id="city" name="city" 
                                                   value="{{ $contractContainerFill->city }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">{{ __('Address') }} *</label>
                                            <input type="text" class="form-control" id="address" name="address" 
                                                   value="{{ $contractContainerFill->address }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="{{ __('Optional notes about this container fill...') }}">{{ $contractContainerFill->notes }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> {{ __('Update Container Fill') }}
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Contract Information -->
                        <div class="card" id="contract-info">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Contract Information') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>{{ __('Customer') }}:</strong>
                                    <div id="contract-customer">{{ $contractContainerFill->contract->customer['name'] ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('City') }}:</strong>
                                    <div id="contract-city">{{ $contractContainerFill->contract->customer['city'] ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Address') }}:</strong>
                                    <div id="contract-address">{{ $contractContainerFill->contract->customer['address'] ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Start Date') }}:</strong>
                                    <div id="contract-start-date">{{ $contractContainerFill->contract->start_date->format('Y-m-d') }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Contract Period') }}:</strong>
                                    <div id="contract-period">{{ $contractContainerFill->contract->contract_period }} {{ __('days') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Information -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Status Information') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>{{ __('Current Status') }}:</strong>
                                    <div>
                                        @if($contractContainerFill->is_discharged)
                                            <span class="badge bg-success">{{ __('Discharged') }}</span>
                                        @elseif($contractContainerFill->is_overdue)
                                            <span class="badge bg-danger">{{ __('Overdue') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('Active') }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($contractContainerFill->is_discharged)
                                    <div class="mb-3">
                                        <strong>{{ __('Discharge Date') }}:</strong>
                                        <div>{{ $contractContainerFill->discharge_date->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('Discharged By') }}:</strong>
                                        <div>{{ $contractContainerFill->discharge->name ?? '' }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Show contract information when contract is selected
    $('#contract_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            const customer = selectedOption.data('customer');
            const city = selectedOption.data('city');
            const address = selectedOption.data('address');
            const startDate = selectedOption.data('start-date');
            const period = selectedOption.data('period');

            $('#contract-customer').text(customer);
            $('#contract-city').text(city);
            $('#contract-address').text(address);
            $('#contract-start-date').text(startDate);
            $('#contract-period').text(period + ' {{ __("days") }}');
            
            // Auto-fill city and address
            $('#city').val(city);
            $('#address').val(address);
            
            // Calculate expected discharge date
            if (startDate && period) {
                const start = new Date(startDate);
                const expectedDate = new Date(start);
                expectedDate.setDate(start.getDate() + parseInt(period));
                $('#expected_discharge_date').val(expectedDate.toISOString().split('T')[0]);
            }
        }
    });

    // Show contract info on page load
    $('#contract_id').trigger('change');
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection


