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
                                <input type="text" class="form-control" id="customer_telephone" name="customer[telephone]" value="{{ old('customer.telephone', $offer->customer['telephone'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_mobile" class="form-label">{{ __('Mobile') }}</label>
                                <input type="text" class="form-control" id="customer_mobile" name="customer[mobile]" value="{{ old('customer.mobile', $offer->customer['mobile'] ?? '') }}">
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
                        <label for="type_id" class="form-label">{{ __('Container Type') }} *</label>
                        <select class="form-select" id="type_id" name="type_id" required>
                            <option value="">{{ __('Choose container type...') }}</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" @selected(old('type_id', $offer->type_id ?? '') == $type->id)>{{ $type->name }}</option>
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
    });
</script>
@endpush