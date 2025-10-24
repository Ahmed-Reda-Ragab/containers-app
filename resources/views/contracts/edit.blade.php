@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Edit Contract') }} #{{ $contract->id }}</h2>
                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">
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

            <form action="{{ route('contracts.update', $contract) }}" method="POST" id="contractForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Customer Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">{{ __('Select Customer') }}</label>
                                    <select class="form-select" id="customer_id" name="customer_id">
                                        <option value="">{{ __('Choose a customer...') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" 
                                                    data-name="{{ $customer->name }}"
                                                    data-contact-person="{{ $customer->contact_person['name']??'' }}"
                                                    data-telephone="{{ $customer->contact_person['phone']??'' }}"
                                                    data-city="{{ $customer->city??'' }}"
                                                    {{ $contract->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_contact_person" class="form-label">{{ __('Contact Person') }}</label>
                                            <input type="text" class="form-control" id="customer_contact_person" name="customer[contact_person]"
                                                   value="{{ $contract->customer['contact_person']['name'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_telephone" class="form-label">{{ __('Telephone') }}</label>
                                            <input type="text" class="form-control" id="customer_telephone" name="customer[telephone]"
                                                   value="{{ $contract->customer['contact_person']['phone'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_mobile" class="form-label">{{ __('Mobile') }}</label>
                                            <input type="text" class="form-control" id="customer_mobile" name="customer[mobile]"
                                                   value="{{ $contract->customer['mobile'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_city" class="form-label">{{ __('City') }}</label>
                                            <input type="text" class="form-control" id="customer_city" name="customer[city]"
                                                   value="{{ $contract->customer['city'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control" id="customer_address" name="customer[address]" rows="3">{{ $contract->customer['address'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Details -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Contract Details') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="size_id" class="form-label">{{ __('Container Type') }} *</label>
                                    <select class="form-select" id="size_id" name="size_id" required>
                                        <option value="">{{ __('Choose container type...') }}</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ $contract->size_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="container_price" class="form-label">{{ __('Container Price') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="container_price" name="container_price" 
                                                       step="0.01" min="0" value="{{ $contract->container_price }}" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="no_containers" class="form-label">{{ __('Number of Containers') }} *</label>
                                            <input type="number" class="form-control" id="no_containers" name="no_containers" 
                                                   min="1" value="{{ $contract->no_containers }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="monthly_dumping_cont" class="form-label">{{ __('Monthly Dumping per Container') }} *</label>
                                            <input type="number" class="form-control" id="monthly_dumping_cont" name="monthly_dumping_cont" 
                                                   step="0.01" min="0" value="{{ $contract->monthly_dumping_cont }}" required>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="dumping_cost" class="form-label">{{ __('Dumping Cost') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="dumping_cost" name="dumping_cost" 
                                                       step="0.01" min="0" value="{{ $contract->dumping_cost }}" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div> -->
                                
                                    <!-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="additional_trip_cost" class="form-label">{{ __('Additional Trip Cost') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="additional_trip_cost" name="additional_trip_cost" 
                                                       step="0.01" min="0" value="{{ $contract->additional_trip_cost }}" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div> -->
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contract_period" class="form-label">{{ __('Contract Period (Days)') }} *</label>
                                            <input type="number" class="form-control" id="contract_period" name="contract_period" 
                                                   min="1" value="{{ $contract->contract_period }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tax_value" class="form-label">{{ __('Tax Value (%)') }} *</label>
                                            <input type="number" class="form-control" id="tax_value" name="tax_value" 
                                                   step="0.01" min="0" max="100" value="{{ $contract->tax_value }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">{{ __('Status') }} *</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="pending" {{ $contract->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                <option value="active" {{ $contract->status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                                <option value="expired" {{ $contract->status == 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                                                <option value="canceled" {{ $contract->status == 'canceled' ? 'selected' : '' }}>{{ __('Canceled') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">{{ __('Start Date') }} *</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                                   value="{{ $contract->start_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">{{ __('End Date') }} *</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                                   value="{{ $contract->end_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Terms and Conditions') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agreement_terms" class="form-label">{{ __('Agreement Terms') }}</label>
                                    <textarea class="form-control" id="agreement_terms" name="agreement_terms" rows="4">{{ $contract->agreement_terms }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="material_restrictions" class="form-label">{{ __('Material Restrictions') }}</label>
                                    <textarea class="form-control" id="material_restrictions" name="material_restrictions" rows="4">{{ $contract->material_restrictions }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_terms" class="form-label">{{ __('Delivery Terms') }}</label>
                                    <textarea class="form-control" id="delivery_terms" name="delivery_terms" rows="4">{{ $contract->delivery_terms }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_policy" class="form-label">{{ __('Payment Policy') }}</label>
                                    <textarea class="form-control" id="payment_policy" name="payment_policy" rows="4">{{ $contract->payment_policy }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $contract->notes }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Calculated Totals -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Calculated Totals') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Monthly Total Dumping Cost') }}</label>
                                    <div class="h4 text-primary" id="monthly_total_dumping_cost_display">{{ number_format($contract->monthly_total_dumping_cost, 2) }} {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Subtotal') }}</label>
                                    <div class="h4 text-info" id="subtotal_display">{{ number_format($contract->monthly_total_dumping_cost + $contract->additional_trip_cost, 2) }} {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Tax Amount') }}</label>
                                    <div class="h4 text-warning" id="tax_amount_display">{{ number_format(($contract->monthly_total_dumping_cost + $contract->additional_trip_cost) * ($contract->tax_value / 100), 2) }} {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Total Price') }}</label>
                                    <div class="h4 text-success" id="total_price_display">{{ number_format($contract->total_price, 2) }} {{ __('SAR') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> {{ __('Update Contract') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill customer data when customer is selected
    $('#customer_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            $('#customer_name').val(selectedOption.data('name'));
            $('#customer_contact_person').val(selectedOption.data('contact-person'));
            $('#customer_telephone').val(selectedOption.data('telephone'));
            $('#customer_ext').val(selectedOption.data('ext'));
            $('#customer_fax').val(selectedOption.data('fax'));
            $('#customer_mobile').val(selectedOption.data('mobile'));
            $('#customer_city').val(selectedOption.data('city'));
            $('#customer_address').val(selectedOption.data('address'));
        }
    });

    // Calculate totals when values change
    function calculateTotals() {
        const dumpingCost = parseFloat($('#dumping_cost').val()) || 0;
        const noContainers = parseInt($('#no_containers').val()) || 0;
        const additionalTripCost = parseFloat($('#additional_trip_cost').val()) || 0;
        const taxValue = parseFloat($('#tax_value').val()) || 0;

        const monthlyTotalDumpingCost = dumpingCost * noContainers;
        const subtotal = monthlyTotalDumpingCost + additionalTripCost;
        const taxAmount = subtotal * (taxValue / 100);
        const totalPrice = subtotal + taxAmount;

        $('#monthly_total_dumping_cost_display').text(monthlyTotalDumpingCost.toFixed(2) + ' {{ __("SAR") }}');
        $('#subtotal_display').text(subtotal.toFixed(2) + ' {{ __("SAR") }}');
        $('#tax_amount_display').text(taxAmount.toFixed(2) + ' {{ __("SAR") }}');
        $('#total_price_display').text(totalPrice.toFixed(2) + ' {{ __("SAR") }}');
    }

    // Bind calculation to input changes
    $('#dumping_cost, #no_containers, #additional_trip_cost, #tax_value').on('input', calculateTotals);

    // Set end date when start date changes
    $('#start_date').change(function() {
        const startDate = new Date($(this).val());
        const endDate = new Date(startDate);
        endDate.setFullYear(endDate.getFullYear() + 1);
        $('#end_date').val(endDate.toISOString().split('T')[0]);
    });

    // Initial calculation
    calculateTotals();
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection

