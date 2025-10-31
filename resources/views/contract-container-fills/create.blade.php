@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Record Container Fill') }}</h2>
                <a href="{{ isset($contract) ? route('contracts.show', $contract) : route('contract-container-fills.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
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

            <form action="{{ route('contract-container-fills.store') }}" method="POST">
                @csrf
                
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
                                                    {{ isset($contract) && $contract->id == $contractOption->id ? 'selected' : '' }}
                                                    data-customer="{{ $contractOption->customer['name'] ?? '' }}"
                                                    data-size_id="{{ $contractOption->size_id }}"
                                                    data-size="{{ $contractOption->size->name??"" }}"
                                                    data-city="{{ $contractOption->customer['city'] ?? '' }}"
                                                    data-address="{{ $contractOption->customer['address'] ?? '' }}"
                                                    data-start-date="{{ $contractOption->start_date->format('Y-m-d') }}"
                                                    data-period="{{ $contractOption->VisiteEveryDay }}"
                                                    
                                                    >
                                                #{{ $contractOption->id }} - {{ $contractOption->customer['name'] ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 d-none">
                                        <div class="mb-3">
                                            <label for="no" class="form-label">{{ __('Container Number') }} *</label>
                                            <input type="number" class="form-control" id="no" name="no" min="1" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="container_id" class="form-label">{{ __('Container') }} *</label>
                                            <select class="form-select" id="container_id" name="container_id" required>
                                                <option value="">{{ __('Choose a container...') }}</option>
                                                @foreach($containers as $container)
                                                    <option value="{{ $container->id }}" 
                                                            data-code="{{ $container->code }}"
                                                            data-size_id="{{ $container->size_id }}"
                                                            data-type="{{ $container->size->name ?? '' }}">
                                                        {{ $container->code }} ( {{ __('Size') }} : {{ $container->size->name ?? '' }})
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
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deliver_at" class="form-label">{{ __('Delivery Date') }} *</label>
                                            <input type="date" class="form-control" id="deliver_at" name="deliver_at" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deliver_car_id" class="form-label">{{ __('Delivery Car') }}</label>
                                            <select class="form-select" id="deliver_car_id" name="deliver_car_id">
                                                <option value="">{{ __('Choose car...') }}</option>
                                                @foreach(App\Models\Car::all(['id', 'number']) as $car)
                                                    <option value="{{ $car->id }}">{{ $car->number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expected_discharge_date" class="form-label">{{ __('Expected Discharge Date') }} *</label>
                                            <input type="date" class="form-control" id="expected_discharge_date" name="expected_discharge_date" required>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">{{ __('Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0">
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Leave empty to use contract price') }}</small>
                                        </div>
                                    </div> -->
                                </div>

                                <!-- <div class="mb-3">
                                    <label for="client_id" class="form-label">{{ __('Client') }} *</label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value="">{{ __('Choose a client...') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div> -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">{{ __('City') }} *</label>
                                            <input type="text" class="form-control" id="city" name="city" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">{{ __('Address') }} *</label>
                                            <input type="text" class="form-control" id="address" name="address" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="{{ __('Optional notes about this container fill...') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="fas fa-truck"></i> {{ __('Record Container Fill') }}
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Contract Information -->
                        <div class="card" id="contract-info" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Contract Information') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>{{ __('Customer') }}:</strong>
                                    <div id="contract-customer">-</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Container Size') }}:</strong>
                                    <div id="contract-size">-</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('City') }}:</strong>
                                    <div id="contract-city">-</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Address') }}:</strong>
                                    <div id="contract-address">-</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Start Date') }}:</strong>
                                    <div id="contract-start-date">-</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Visit Every') }}:</strong>
                                    <div id="contract-period">-</div>
                                </div>
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
    const contractInfo = $('#contract-info');

    if (selectedOption.val()) {
        const customer = selectedOption.data('customer');
        const city = selectedOption.data('city');
        const size = selectedOption.data('size');
        const address = selectedOption.data('address');
        const startDate = selectedOption.data('start-date');
        const period = selectedOption.data('period');
        const sizeId = selectedOption.data('size_id'); // Get the contract's size_id

        $('#contract-customer').text(customer);
        $('#contract-size').text(size);
        $('#contract-city').text(city);
        $('#contract-address').text(address);
        $('#contract-start-date').text(startDate);
        $('#contract-period').text(period + ' ' + '{{ __("days") }}');

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

        // Show contract info
        contractInfo.show();

        // ===== Filter containers by size_id =====
        const containerSelect = $('#container_id');
        const allOptions = containerSelect.find('option');

        // Store all options initially if not already stored
        if (!containerSelect.data('all-options')) {
            containerSelect.data('all-options', allOptions.clone());
        }

        const filteredOptions = containerSelect
            .data('all-options')
            .filter(function() {
                const optionSizeId = $(this).data('size_id');
                return !optionSizeId || optionSizeId == sizeId; // keep default empty option or matching ones
            });

        containerSelect.html(filteredOptions);
        containerSelect.val(''); // reset selection
    } else {
        // Hide contract info if none selected
        contractInfo.hide();
    }
});


    // Set default delivery date to today
    $('#deliver_at').val(new Date().toISOString().split('T')[0]);

    // When delivery date changes, update expected discharge date
    $('#deliver_at').on('change', function() {
        const deliverDate = $(this).val();
        const selectedOption = $('#contract_id').find('option:selected');
        const period = selectedOption.data('period');

        if (deliverDate && period) {
            const start = new Date(deliverDate);
            const expectedDate = new Date(start);
            expectedDate.setDate(start.getDate() + parseInt(period));
            $('#expected_discharge_date').val(expectedDate.toISOString().split('T')[0]);
        }
    });

    // Show contract info on page load if contract is pre-selected
    if ($('#contract_id').val()) {
        $('#contract_id').trigger('change');
    }
});

</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection

