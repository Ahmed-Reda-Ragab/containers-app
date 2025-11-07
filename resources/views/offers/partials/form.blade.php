<form action="{{ $action }}" method="POST">
    @csrf
    @if($method === 'PUT')
    @method('PUT')
    @endif

    <div class="row">
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
            data-phone="{{ $customer->phone ?? '' }}"
            data-contact-person="{{ $customer->contact_person['name'] ?? '' }}"
            data-contact-phone="{{ $customer->contact_person['phone'] ?? '' }}"
            data-city="{{ $customer->city ?? '' }}"
            data-type="{{ $customer->type ?? '' }}"
            data-tax_number="{{ $customer->tax_number ?? '' }}"
            data-commercial_number="{{ $customer->commercial_number ?? '' }}"
            data-address="{{ $customer->address ?? '' }}">
            {{ $customer->name }}
        </option>
    @endforeach


                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="customer_name" class="form-label">{{ __('Customer Name') }} *</label>
                        <input type="text" class="form-control" id="customer_name" name="customer[name]" value="{{ old('customer.name', $offer->customer['name'] ?? '') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_contact_person" class="form-label">{{ __('Contact Person') }}</label>
                                <input type="text" class="form-control" id="customer_contact_person" name="customer[contact_person]" value="{{ old('customer.contact_person', $offer->customer['contact_person'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_telephone" class="form-label">{{ __('Telephone') }}</label>
                                <input type="text" class="form-control" id="customer_telephone" name="customer[phone]" value="{{ old('customer.telephone', $offer->customer['telephone'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_mobile" class="form-label">{{ __('Mobile') }}</label>
                                <input type="text" class="form-control" id="customer_mobile" name="customer[contact_phone]" value="{{ old('customer.mobile', $offer->customer['mobile'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_city" class="form-label">{{ __('City') }}</label>
                                <input type="text" class="form-control" id="customer_city" name="customer[city]" value="{{ old('customer.city', $offer->customer['city'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customer_address" class="form-label">{{ __('Address') }}</label>
                        <textarea class="form-control" id="customer_address" name="customer[address]" rows="3">{{ old('customer.address', $offer->customer['address'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Offer Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="size_id" class="form-label">{{ __('Container Type') }} *</label>
                        <select class="form-select" id="size_id" name="size_id" required>
                            <option value="">{{ __('Choose container type...') }}</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" @selected(old('size_id', $offer->size_id ?? '') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="container_price" class="form-label">{{ __('Container Price') }} *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="container_price" name="container_price" step="1" min="0" value="{{ old('container_price', $offer->container_price ?? '') }}" required>
                                    <span class="input-group-text">{{ __('SAR') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_containers" class="form-label">{{ __('Number of Containers') }} *</label>
                                <input type="number" class="form-control" id="no_containers" name="no_containers" min="1" value="{{ old('no_containers', $offer->no_containers ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="monthly_dumping_cont" class="form-label">{{ __('Monthly Dumping per Container') }} *</label>
                                <input type="number" class="form-control" id="monthly_dumping_cont" name="monthly_dumping_cont" step="1" min="0" value="{{ old('monthly_dumping_cont', $offer->monthly_dumping_cont ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="additional_trip_cost" class="form-label">{{ __('Additional Trip Cost') }} *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="additional_trip_cost" name="additional_trip_cost" step="0.1" min="0" value="{{ old('additional_trip_cost', $offer->additional_trip_cost ?? '') }}" required>
                                    <span class="input-group-text">{{ __('SAR') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contract_period" class="form-label">{{ __('Contract Period (Months)') }} *</label>
                                <input type="number" class="form-control" id="contract_period" name="contract_period" min="1" value="{{ old('contract_period', $offer->contract_period ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_value" class="form-label">{{ __('Tax Value (%)') }} *</label>
                                <input type="number" class="form-control" id="tax_value" name="tax_value" step="0.01" min="0" max="100" readonly value="{{ old('tax_value', $offer->tax_value ?? 15) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d') ) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', optional($offer->end_date ?? null)->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Terms and Conditions') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="agreement_terms" class="form-label">{{ __('Agreement Terms') }}</label>
                        <textarea class="form-control" id="agreement_terms" name="agreement_terms" rows="4">{{ old('agreement_terms', $offer->agreement_terms ?? '') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="material_restrictions" class="form-label">{{ __('Material Restrictions') }}</label>
                        <textarea class="form-control" id="material_restrictions" name="material_restrictions" rows="4">{{ old('material_restrictions', $offer->material_restrictions ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="delivery_terms" class="form-label">{{ __('Delivery Terms') }}</label>
                        <textarea class="form-control" id="delivery_terms" name="delivery_terms" rows="4">{{ old('delivery_terms', $offer->delivery_terms ?? '') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_policy" class="form-label">{{ __('Payment Policy') }}</label>
                        <textarea class="form-control" id="payment_policy" name="payment_policy" rows="4">{{ old('payment_policy', $offer->payment_policy ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $offer->notes ?? '') }}</textarea>
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
                            <div class="col-md-6">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Monthly Total Dumping Cost') }}</label>
                                    <div class="h4 text-primary" id="monthly_total_dumping_cost_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 d-none">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Subtotal') }}</label>
                                    <div class="h4 text-info" id="subtotal_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 d-none">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Tax Amount') }}</label>
                                    <div class="h4 text-warning" id="tax_amount_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <label class="form-label">{{ __('Total Contract Price') }}</label>
                                    <div class="h4 text-success" id="total_contract_price_display">0.00 {{ __('SAR') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> {{ $method === 'PUT' ? __('Update Offer') : __('Create Offer') }}
        </button>
    </div>
</form>


@push('scripts')
<script>
    $(document).ready(function() {
        const queryOfferId = new URLSearchParams(window.location.search).get('offer_id');

        // Auto-fill customer data when customer is selected
        
    $('#customer_id').on('change', function () {
        const selected = $(this).find('option:selected');

        // Reset if no customer selected
        if (!selected.val()) {
            $('#customer_name').val('').prop('readonly', false);
            $('#customer_contact_tax_number, #customer_commercial_number, #customer_contact_person, #customer_telephone, #customer_mobile, #customer_city, #customer_address').val('');
            $('.is-company').hide();
            return;
        }

        // Extract data
        const name = selected.data('name') || '';
        const phone = selected.data('phone') || '';
        const contactPerson = selected.data('contact-person') || '';
        const contactPhone = selected.data('contact-phone') || '';
        const city = selected.data('city') || '';
        const type = selected.data('type') || '';
        const taxNumber = selected.data('tax_number') || '';
        const commercialNumber = selected.data('commercial_number') || '';
        const address = selected.data('address') || '';

        // Fill values
        $('#customer_name').val(name).prop('readonly', true);
        $('#customer_mobile').val(phone);
        $('#customer_contact_person').val(contactPerson);
        $('#customer_telephone').val(contactPhone);
        $('#customer_city').val(city);
        $('#customer_contact_tax_number').val(taxNumber);
        $('#customer_commercial_number').val(commercialNumber);
        $('#customer_address').val(address || '');

        // Show/hide business fields
        if (type === 'business') {
            $('.is-company').slideDown();
        } else {
            $('.is-company').slideUp();
        }
    });

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
        function formatDate(date) {
            const d = new Date(date);
            const month = ('0' + (d.getMonth() + 1)).slice(-2);
            const day = ('0' + d.getDate()).slice(-2);
            return `${d.getFullYear()}-${month}-${day}`;
        }

        function calculateTotals() {
            const container_price = parseFloat($('#container_price').val()) || 0;
            const noContainers = parseInt($('#no_containers').val()) || 0;
            const monthly_dumping_cont = parseFloat($('#monthly_dumping_cont').val()) || 0;
            const additional_trip_cost = parseFloat($('#additional_trip_cost').val()) || 0;
            const tax_value = parseFloat($('#tax_value').val()) || 0;
            const contract_period = parseInt($('#contract_period').val()) || 1;

            const monthly_total_dumping_cost = container_price * noContainers;
            const total_contract_price = monthly_total_dumping_cost * contract_period;
            const subtotal = monthly_total_dumping_cost;
            const tax_amount = subtotal * (tax_value / 100);
            const total_price = subtotal + tax_amount;

            $('#monthly_total_dumping_cost_display').text(monthly_total_dumping_cost.toFixed(2) + ' {{ __("SAR") }}');
            $('#subtotal_display').text(subtotal.toFixed(2) + ' {{ __("SAR") }}');
            $('#tax_amount_display').text(tax_amount.toFixed(2) + ' {{ __("SAR") }}');
            $('#total_price_display').text(total_price.toFixed(2) + ' {{ __("SAR") }}');
            $('#total_contract_price_display').text(total_contract_price.toFixed(2) + ' {{ __("SAR") }}');
        }
        $('#container_price, #no_containers, #additional_trip_cost, #tax_value, #contract_period').on('input', calculateTotals);

    });
</script>
@endpush