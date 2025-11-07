@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Edit Payment') }}</h2>
                <a href="{{ route('contracts.show', $payment->contract) }}" class="btn btn-secondary">
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

            <form action="{{ route('payments.update', $payment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">

                                    <h5 class="mb-0">{{ __('Payment Information') }}</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="contract_id" class="form-label">{{ __('Contract') }} *</label>
                                    <select class="form-select" id="contract_id" name="contract_id" required>
                                        <option value="">{{ __('Choose a contract...') }}</option>
                                        @foreach($contracts as $contractOption)
                                        <option value="{{ $contractOption->id }}"
                                            {{ $payment->contract_id == $contractOption->id ? 'selected' : '' }}
                                            data-customer="{{ $contractOption->customer['name'] ?? '' }}"
                                            data-total-price="{{ $contractOption->total_price }}"
                                            data-total-payed="{{ $contractOption->total_payed }}"
                                            data-remaining="{{ $contractOption->remaining_amount }}">
                                            #{{ $contractOption->id }} - {{ $contractOption->customer['name'] ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payed" class="form-label">{{ __('Amount') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="payed" name="payed"
                                                    step="0.01" min="0.01" value="{{ $payment->payed }}" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="method" class="form-label">{{ __('Payment Method') }}</label>
                                            <select class="form-select" id="method" name="method">
                                                <option value="">{{ __('Choose method...') }}</option>
                                                <option value="cash" {{ $payment->method == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                                <option value="bank_transfer" {{ $payment->method == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                                                <option value="check" {{ $payment->method == 'check' ? 'selected' : '' }}>{{ __('Check') }}</option>
                                                <option value="credit_card" {{ $payment->method == 'credit_card' ? 'selected' : '' }}>{{ __('Credit Card') }}</option>
                                                <option value="other" {{ $payment->method == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_payed" name="is_payed" value="1" {{ $payment->is_payed ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_payed">
                                            {{ __('Payment is completed') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="{{ __('Optional notes about this payment...') }}">{{ $payment->notes }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> {{ __('Update Payment') }}
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Contract Summary -->
                        <div class="card" id="contract-summary">
                            <div class="card-header">
                                <div class="card-title">

                                    <h6 class="mb-0">{{ __('Contract Summary') }}</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>{{ __('Customer') }}:</strong>
                                    <div id="contract-customer">{{ $payment->contract->customer['name'] ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Total Price') }}:</strong>
                                    <div id="contract-total-price">{{ number_format($payment->contract->total_price, 2) }} {{ __('SAR') }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Total Paid') }}:</strong>
                                    <div id="contract-total-paid">{{ number_format($payment->contract->total_payed, 2) }} {{ __('SAR') }}</div>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Remaining Amount') }}:</strong>
                                    <div id="contract-remaining" class="text-danger">{{ number_format($payment->contract->remaining_amount, 2) }} {{ __('SAR') }}</div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar" id="payment-progress" role="progressbar"
                                        style="width: {{ $payment->contract->total_price > 0 ? ($payment->contract->total_payed / $payment->contract->total_price) * 100 : 0 }}%"></div>
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
            }
        });

        // Set max payment amount to remaining amount
        $('#contract_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.val()) {
                const remaining = parseFloat(selectedOption.data('remaining'));
                $('#payed').attr('max', remaining);
            }
        });

        // Initial calculation
        $('#contract_id').trigger('change');
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection