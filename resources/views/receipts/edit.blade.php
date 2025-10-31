@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Edit Receipt') }}</h2>
                <div>
                    <a href="{{ route('receipts.show', $receipt) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> {{ __('View') }}
                    </a>
                    <a href="{{ route('receipts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Receipts') }}
                    </a>
                </div>
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
                    <form action="{{ route('receipts.update', $receipt) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Receipt Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="receipt_number" class="form-label">{{ __('Receipt Number') }}</label>
                                            <input type="text" class="form-control" id="receipt_number" value="{{ $receipt->receipt_number }}" readonly>
                                            <small class="form-text text-muted">{{ __('Receipt number cannot be changed') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">{{ __('Status') }} *</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="issued" {{ old('status', $receipt->status) === 'issued' ? 'selected' : '' }}>{{ __('Issued') }}</option>
                                                <option value="collected" {{ old('status', $receipt->status) === 'collected' ? 'selected' : '' }}>{{ __('Collected') }}</option>
                                                <option value="overdue" {{ old('status', $receipt->status) === 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                                                <option value="cancelled" {{ old('status', $receipt->status) === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contract_id" class="form-label">{{ __('Contract') }} *</label>
                                            <select class="form-select" id="contract_id" name="contract_id" required>
                                                <option value="">{{ __('Choose a contract...') }}</option>
                                                @foreach($contracts as $contract)
                                                    <option value="{{ $contract->id }}" {{ old('contract_id', $receipt->contract_id) == $contract->id ? 'selected' : '' }}
                                                            data-customer="{{ $contract->customer['name'] ?? '' }}"
                                                            data-phone="{{ $contract->customer['mobile'] ?? $contract->customer['telephone'] ?? '' }}"
                                                            data-address="{{ $contract->customer['address'] ?? '' }}"
                                                            data-city="{{ $contract->customer['city'] ?? '' }}">
                                                        #{{ $contract->id }} - {{ $contract->customer['name'] ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">{{ __('Customer') }} *</label>
                                            <select class="form-select" id="customer_id" name="customer_id" required>
                                                <option value="">{{ __('Choose a customer...') }}</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ old('customer_id', $receipt->customer_id) == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label">{{ __('Customer Name') }} *</label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                                   value="{{ old('customer_name', $receipt->customer_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label">{{ __('Customer Phone') }} *</label>
                                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" 
                                                   value="{{ old('customer_phone', $receipt->customer_phone) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">{{ __('Customer Address') }} *</label>
                                    <textarea class="form-control" id="customer_address" name="customer_address" rows="2" required>{{ old('customer_address', $receipt->customer_address) }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">{{ __('City') }} *</label>
                                            <input type="text" class="form-control" id="city" name="city" 
                                                   value="{{ old('city', $receipt->city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">{{ __('Amount') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="amount" name="amount" 
                                                       step="0.01" min="0.01" value="{{ old('amount', $receipt->amount) }}" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="issue_date" class="form-label">{{ __('Issue Date') }}</label>
                                            <input type="date" class="form-control" id="issue_date" value="{{ $receipt->issue_date->format('Y-m-d') }}" readonly>
                                            <small class="form-text text-muted">{{ __('Issue date cannot be changed') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">{{ __('Due Date') }} *</label>
                                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                                   value="{{ old('due_date', $receipt->due_date->format('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="{{ __('Optional notes about this receipt...') }}">{{ old('notes', $receipt->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> {{ __('Update Receipt') }}
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
                                <strong>{{ __('Phone') }}:</strong>
                                <div id="contract-phone">-</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Address') }}:</strong>
                                <div id="contract-address">-</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('City') }}:</strong>
                                <div id="contract-city">-</div>
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
    // Auto-fill customer data when contract is selected
    $('#contract_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            const customer = selectedOption.data('customer');
            const phone = selectedOption.data('phone');
            const address = selectedOption.data('address');
            const city = selectedOption.data('city');

            // Auto-fill form fields
            $('#customer_name').val(customer);
            $('#customer_phone').val(phone);
            $('#customer_address').val(address);
            $('#city').val(city);

            // Show contract summary
            $('#contract-customer').text(customer);
            $('#contract-phone').text(phone);
            $('#contract-address').text(address);
            $('#contract-city').text(city);
            $('#contract-summary').show();
        } else {
            $('#contract-summary').hide();
        }
    });

    // Show contract summary on page load if contract is selected
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


