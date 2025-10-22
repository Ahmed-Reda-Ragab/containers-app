@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Create Contract') }}</h2>
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Contracts') }}
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

            <!-- Offer Loader -->
            <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('Load From Offer') }}</h5>
                                <small class="text-muted">{{ __('Search by offer # or client name') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-8">
                                        <label for="offer_select" class="form-label">{{ __('Select Offer') }}</label>
                                        <select id="offer_select" class="form-select" style="width: 100%"></select>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{ route('contracts.convert-from-offer') }}" method="POST" id="convertOfferForm">
                                            @csrf
                                            <input type="hidden" name="offer_id" id="convert_offer_id">
                                            <button type="submit" class="btn btn-outline-primary">
                                                <i class="fas fa-exchange-alt"></i> {{ __('Convert Offer to Contract') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <form action="{{ route('contracts.store') }}" method="POST" id="contractForm">
                @csrf

                <div class="row">
                    
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Customer Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">{{ __('Select Customer') }} *</label>
                                    <select class="form-select" id="customer_id" name="customer_id">
                                        <option value="">{{ __('Choose a customer...') }}</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            data-name="{{ $customer->name }}"
                                            data-contact-person="{{ $customer->contact_person['name']??'' }}"
                                            data-telephone="{{ $customer->contact_person['phone']??'' }}"
                                            data-city="{{ $customer->city??'' }}"
                                            data-type="{{ $customer->type??'' }}"
                                            data-tax_number="{{ $customer->tax_number??'' }}"
                                            data-commercial_number="{{ $customer->commercial_number??'' }}">
                                            {{ $customer->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">{{ __('Customer Name') }} *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer[name]" required>
                                </div>

                                @if ( isset($type) && $type == 'business')

                                    <div class="row is-company ">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="customer_contact_tax_number" class="form-label">{{ __('Tax Number') }}</label>
                                                <input type="text" class="form-control" id="customer_contact_tax_number" name="customer[tax_number]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="customer_commercial_number" class="form-label">{{ __('Commercial Number') }}</label>
                                                <input type="text" class="form-control" id="customer_commercial_number" name="customer[commercial_number]">
                                            </div>
                                        </div>
                                    </div>     
                                @endif
                               


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_contact_person" class="form-label">{{ __('Contact Person') }}</label>
                                            <input type="text" class="form-control" id="customer_contact_person" name="customer[contact_person]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_telephone" class="form-label">{{ __('Telephone') }}</label>
                                            <input type="text" class="form-control" id="customer_telephone" name="customer[contact_phone]">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_mobile" class="form-label">{{ __('Mobile') }}</label>
                                            <input type="text" class="form-control" id="customer_mobile" name="customer[mobile]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_city" class="form-label">{{ __('City') }}</label>
                                            <input type="text" class="form-control" id="customer_city" name="customer[city]">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control" id="customer_address" name="customer[address]" rows="3"></textarea>
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
                                    <label for="type_id" class="form-label">{{ __('Container Type') }} *</label>
                                    <select class="form-select" id="type_id" name="type_id" required>
                                        <option value="">{{ __('Choose container type...') }}</option>
                                        @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="container_price" class="form-label">{{ __('Container Price') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="container_price" name="container_price" step="0.01" min="0" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="no_containers" class="form-label">{{ __('Number of Containers') }} *</label>
                                            <input type="number" class="form-control" id="no_containers" name="no_containers" value="1" min="1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 {{ isset($type) && $type != 'business' ? 'd-none' : '' }}">
                                        <div class="mb-3">
                                            <label for="monthly_dumping_cont" class="form-label">{{ __('Monthly Dumping per Container') }} *</label>
                                            <input type="number" class="form-control" id="monthly_dumping_cont" name="monthly_dumping_cont" step="1" value="1" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-none">
                                        <div class="mb-3">
                                            <label for="dumping_cost" class="form-label">{{ __('Dumping Cost') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="dumping_cost" name="dumping_cost" step="0.01" min="0">
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 {{ isset($type) && $type != 'business' ? 'd-none' : '' }}">
                                        <div class="mb-3">
                                            <label for="additional_trip_cost" class="form-label">{{ __('Additional Trip Cost') }} *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="additional_trip_cost" name="additional_trip_cost" step="0.01" min="0" value="0" required>
                                                <span class="input-group-text">{{ __('SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contract_period" class="form-label">{{ __('Contract Period (Months)') }} *</label>
                                            <input type="number" class="form-control" id="contract_period" name="contract_period" min="1" value="1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tax_value" class="form-label">{{ __('Tax Value (%)') }} *</label>
                                            <input type="number" class="form-control" id="tax_value" name="tax_value" readonly step="0.01" min="0" max="100" value="15" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">{{ __('Status') }} *</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="pending">{{ __('Pending') }}</option>
                                                <option value="active">{{ __('Active') }}</option>
                                                <option value="expired">{{ __('Expired') }}</option>
                                                <option value="canceled">{{ __('Canceled') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">{{ __('Start Date') }} *</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d') ) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">{{ __('End Date') }} *</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" required>
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
                                    <textarea class="form-control" id="agreement_terms" name="agreement_terms" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="material_restrictions" class="form-label">{{ __('Material Restrictions') }}</label>
                                    <textarea class="form-control" id="material_restrictions" name="material_restrictions" rows="4"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_terms" class="form-label">{{ __('Delivery Terms') }}</label>
                                    <textarea class="form-control" id="delivery_terms" name="delivery_terms" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_policy" class="form-label">{{ __('Payment Policy') }}</label>
                                    <textarea class="form-control" id="payment_policy" name="payment_policy" rows="4"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
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
                                    <div class="h4 text-primary" id="monthly_total_dumping_cost_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Subtotal') }}</label>
                                    <div class="h4 text-info" id="subtotal_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Tax Amount') }}</label>
                                    <div class="h4 text-warning" id="tax_amount_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Total Price') }}</label>
                                    <div class="h4 text-success" id="total_price_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> {{ __('Create Contract') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const queryOfferId = new URLSearchParams(window.location.search).get('offer_id');

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
                // if (selectedOption.data('type') === 'company') {
                //     $('.is-company').show();
                // } else {
                //     $('.is-company').hide();
                // }
                $('#customer_tax_number').val(selectedOption.data('tax_number'));
                $('#customer_commercial_number').val(selectedOption.data('commercial_number'));
                $('#customer_address').val(selectedOption.data('address'));
                $('#customer_type').val(selectedOption.data('type'));
            }
        });

        // Calculate totals when values change
        function calculateTotals() {
            const container_price = parseFloat($('#container_price').val()) || 0;
            const noContainers = parseInt($('#no_containers').val()) || 0;
            const monthly_dumping_cont = parseFloat($('#monthly_dumping_cont').val()) || 0;
            const additional_trip_cost = parseFloat($('#additional_trip_cost').val()) || 0;
            const tax_value = parseFloat($('#tax_value').val()) || 0;

            const monthly_total_dumping_cost = container_price * noContainers * monthly_dumping_cont;
            const subtotal = monthly_total_dumping_cost ;
            const tax_amount = subtotal * (tax_value / 100);
            const total_price = subtotal + tax_amount;

            $('#monthly_total_dumping_cost_display').text(monthly_total_dumping_cost.toFixed(2) + ' {{ __("SAR") }}');
            $('#subtotal_display').text(subtotal.toFixed(2) + ' {{ __("SAR") }}');
            $('#tax_amount_display').text(tax_amount.toFixed(2) + ' {{ __("SAR") }}');
            $('#total_price_display').text(total_price.toFixed(2) + ' {{ __("SAR") }}');
        }

        // Bind calculation to input changes
        $('#dumping_cost, #no_containers, #additional_trip_cost, #tax_value').on('input', calculateTotals);

        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        $('#start_date').val(today);

        

        // Initial calculation
        calculateTotals();

        // Select2 for offers
        function ensureSelect2Loaded(cb) {
            if ($.fn.select2) {
                cb();
                return;
            }
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
            document.head.appendChild(link);
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
            script.onload = cb;
            document.body.appendChild(script);
        }

        function prefillFromOffer(offerId) {
            if (!offerId) return;
            $('#convert_offer_id').val(offerId);
            $.getJSON('{{ route('offers.data', ['offer' => 'OFFER_ID']) }}'.replace('OFFER_ID', offerId),
                function(data) {
                    if (data.customer) {
                        $('#customer_name').val(data.customer.name || '');
                        $('#customer_contact_person').val(data.customer.contact_person || '');
                        $('#customer_telephone').val(data.customer.telephone || data.customer.contact_phone || '');
                        $('#customer_mobile').val(data.customer.mobile || '');
                        $('#customer_city').val(data.customer.city || '');
                        $('#customer_address').val(data.customer.address || '');
                    }
                    if (data.customer_id) {
                        $('#customer_id').val(data.customer_id).trigger('change');
                    }
                    if (data.type_id) $('#type_id').val(data.type_id).trigger('change');
                    if (data.container_price) $('#container_price').val(data.container_price);
                    if (data.no_containers) $('#no_containers').val(data.no_containers);
                    if (data.monthly_dumping_cont) $('#monthly_dumping_cont').val(data.monthly_dumping_cont);
                    if (data.additional_trip_cost) $('#additional_trip_cost').val(data.additional_trip_cost);
                    if (data.contract_period) $('#contract_period').val(data.contract_period);
                    if (data.tax_value) $('#tax_value').val(data.tax_value);
                    if (data.start_date) $('#start_date').val(data.start_date);
                    if (data.end_date) $('#end_date').val(data.end_date);
                    if (data.notes) $('#notes').val(data.notes);
                    if (data.agreement_terms) $('#agreement_terms').val(data.agreement_terms);
                    if (data.material_restrictions) $('#material_restrictions').val(data.material_restrictions);
                    if (data.delivery_terms) $('#delivery_terms').val(data.delivery_terms);
                    if (data.payment_policy) $('#payment_policy').val(data.payment_policy);
                    calculateTotals();
                });
        }

        ensureSelect2Loaded(function() {
            $('#offer_select').select2({
                placeholder: '{{ __('Search offers...') }}',
                allowClear: true,
                ajax: {
                    url: '{{ route('offers.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return data;
                    }
                }
            }).on('select2:select', function(e) {
                const offerId = e.params.data.id;
                prefillFromOffer(offerId);
            });

            if (queryOfferId) {
                prefillFromOffer(queryOfferId);
            }
        });


        function formatDate(date) {
            const d = new Date(date);
            const month = ('0' + (d.getMonth() + 1)).slice(-2);
            const day = ('0' + d.getDate()).slice(-2);
            return `${d.getFullYear()}-${month}-${day}`;
        }

        // Set end date to 1 year from start date
        $('#contract_period , #start_date').on('input', function() {
            console.log('change');
            setEndDate();
        });

        function setEndDate() {
            let startDateVal = $('#start_date').val();
            let months = parseInt($('#contract_period').val());
            if (!months || months < 1 || !startDateVal) return;


            // if start date not set, use today's date
            let startDate = startDateVal ? new Date(startDateVal) : new Date();
            $('#start_date').val(formatDate(startDate));

            // calculate end date by adding months
            let endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + months);

            $('#end_date').val(formatDate(endDate));

        }

    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection