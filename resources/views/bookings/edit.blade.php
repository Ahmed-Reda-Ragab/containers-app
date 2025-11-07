@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Edit Booking') }}</h2>
                <div>
                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> {{ __('View') }}
                    </a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Bookings') }}
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
                    <form action="{{ route('bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">

                                    <h5 class="mb-0">{{ __('Booking Information') }}</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="booking_date" class="form-label">{{ __('Booking Date') }} *</label>
                                            <input type="date" class="form-control" id="booking_date" name="booking_date"
                                                value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">{{ __('Status') }} *</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                                <option value="delivered" {{ old('status', $booking->status) === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                                <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
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
                                                <option value="{{ $contract->id }}" {{ old('contract_id', $booking->contract_id) == $contract->id ? 'selected' : '' }}
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
                                            <label for="container_id" class="form-label">{{ __('Container') }} *</label>
                                            <select class="form-select" id="container_id" name="container_id" required>
                                                <option value="">{{ __('Choose a container...') }}</option>
                                                @foreach($containers as $container)
                                                <option value="{{ $container->id }}" {{ old('container_id', $booking->container_id) == $container->id ? 'selected' : '' }}>
                                                    {{ $container->code }} - {{ $container->size->name ?? 'Unknown' }} ({{ $container->size ?? '' }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">{{ __('Customer') }} *</label>
                                            <select class="form-select" id="customer_id" name="customer_id" required>
                                                <option value="">{{ __('Choose a customer...') }}</option>
                                                @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="driver_id" class="form-label">{{ __('Driver') }} *</label>
                                            <select class="form-select" id="driver_id" name="driver_id" required>
                                                <option value="">{{ __('Choose a driver...') }}</option>
                                                @foreach($drivers as $driver)
                                                <option value="{{ $driver->id }}" {{ old('driver_id', $booking->driver_id) == $driver->id ? 'selected' : '' }}>
                                                    {{ $driver->name }}
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
                                                value="{{ old('customer_name', $booking->customer_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label">{{ __('Customer Phone') }} *</label>
                                            <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                                                value="{{ old('customer_phone', $booking->customer_phone) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="delivery_address" class="form-label">{{ __('Delivery Address') }} *</label>
                                    <textarea class="form-control" id="delivery_address" name="delivery_address" rows="2" required>{{ old('delivery_address', $booking->delivery_address) }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">{{ __('City') }} *</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                value="{{ old('city', $booking->city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">{{ __('Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="price" name="price"
                                                    step="0.01" min="0" value="{{ old('price', $booking->price) }}">
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="{{ __('Optional notes about this booking...') }}">{{ old('notes', $booking->notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> {{ __('Update Booking') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <!-- Contract Summary -->
                    <div class="card" id="contract-summary" style="display: none;">
                        <div class="card-header">
                            <div class="card-title">

                                <h6 class="mb-0">{{ __('Contract Summary') }}</h6>
                            </div>
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
                $('#delivery_address').val(address);
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