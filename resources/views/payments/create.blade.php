@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Record Payment') }}</h2>
                <a href="{{ isset($contract) ? route('contracts.show', $contract) : route('payments.index') }}" class="btn btn-secondary">
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

            <div class="row">
                <div class="col-md-8">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Payment Information') }}</h5>
                            </div>
                            <div class="card-body">
                                
                                <div class="mb-3">
                                    <label for="contract_id" class="form-label">{{ __('Contract') }} *</label>
                                    <select class="form-select" id="contract_id" name="contract_id" required>
                                        <option value="">{{ __('Choose a contract...') }}</option>
                                        @if($contract)
                                        <option value="{{ $contract->id }}" 
                                                    {{ isset($contract) && $contract->id == $contract->id ? 'selected' : '' }}
                                                    data-customer="{{ $contract->customer['name'] ?? 'N/A' }}"
                                                    data-total-price="{{ $contract->total_price }}"
                                                    data-total-payed="{{ $contract->total_payed }}"
                                                    data-remaining="{{ $contract->remaining_amount }}">
                                                #{{ $contract->id }} - {{ $contract->customer['name'] ?? 'N/A' }}
                                            </option>
                                        @else
                                        @foreach($contracts as $contractOption)
                                            <option value="{{ $contractOption->id }}" 
                                                    {{ isset($contract) && $contract->id == $contractOption->id ? 'selected' : '' }}
                                                    data-customer="{{ $contractOption->customer['name'] ?? 'N/A' }}"
                                                    data-total-price="{{ $contractOption->total_price }}"
                                                    data-total-payed="{{ $contractOption->total_payed }}"
                                                    data-remaining="{{ $contractOption->remaining_amount }}">
                                                #{{ $contractOption->id }} - {{ $contractOption->customer['name'] ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                                                                @endif

                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payed" class="form-label">{{ __('Amount') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="payed" name="payed" 
                                                       step="1" min="1" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="method" class="form-label">{{ __('Payment Method') }}</label>
                                            <select class="form-select" id="method" name="method">
                                                <option value="">{{ __('Choose method...') }}</option>
                                                <option value="cash">{{ __('Cash') }}</option>
                                                <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                                <option value="check">{{ __('Check') }}</option>
                                                <option value="credit_card">{{ __('Credit Card') }}</option>
                                                <option value="other">{{ __('Other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_payed" name="is_payed" value="1" checked>
                                        <label class="form-check-label" for="is_payed">
                                            {{ __('Payment is completed') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="{{ __('Optional notes about this payment...') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-money-bill"></i> {{ __('Record Payment') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <!-- Contract Summary -->
                    <div class="card" id="contract-summary" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Contract Summary') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('Customer') }}:</strong>
                                <div id="contract-customer">-</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Total Price') }}:</strong>
                                <div id="contract-total-price">-</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Total Paid') }}:</strong>
                                <div id="contract-total-paid">-</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Remaining Amount') }}:</strong>
                                <div id="contract-remaining" class="text-danger">-</div>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar" id="payment-progress" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Show contract summary when contract is selected
    $('#contract_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            const customer = selectedOption.data('customer');
            const totalPrice = parseFloat(selectedOption.data('total-price'));
            const totalPayed = parseFloat(selectedOption.data('total-payed'));
            const remaining = parseFloat(selectedOption.data('remaining'));
            const progressPercentage = totalPrice > 0 ? (totalPayed / totalPrice) * 100 : 0;

            $('#contract-customer').text(customer);
            $('#contract-total-price').text(totalPrice.toFixed(2) + ' {{ __("SAR") }}');
            $('#contract-total-paid').text(totalPayed.toFixed(2) + ' {{ __("SAR") }}');
            $('#contract-remaining').text(remaining.toFixed(2) + ' {{ __("SAR") }}');
            $('#payment-progress').css('width', progressPercentage + '%').attr('aria-valuenow', progressPercentage);
            
            $('#contract-summary').show();
        } else {
            $('#contract-summary').hide();
        }
    });

    // Set max payment amount to remaining amount
    $('#contract_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            // const remaining = parseFloat(selectedOption.data('remaining'));
            // $('#payed').attr('max', remaining);
        }
    });

    // Show contract summary on page load if contract is pre-selected
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
