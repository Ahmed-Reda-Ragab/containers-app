@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Quick Individual Contract') }}</h2>
                <a href="{{ route('contracts.index.by-type', 'individual') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Contracts') }}
                </a>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('contracts.quick.individual.store') }}" class="send-ajax">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="mb-0">{{ __('Customer') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Search Customer') }}</label>
                                <input type="text" class="form-control" id="customer_search" placeholder="{{ __('Type name or phone...') }}">
                                <div id="customer_results" class="list-group mt-2 d-none"></div>
                                <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Name') }} *</label>
                                <input type="text" name="customer[name]" id="customer_name" class="form-control" value="{{ old('customer.name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Mobile') }}</label>
                                <input type="text" name="customer[phone]" id="customer_mobile" class="form-control" value="{{ old('customer.phone') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('City') }}</label>
                                <input type="text" name="customer[city]" id="customer_city" class="form-control" value="{{ old('customer.city') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Address') }}</label>
                                <input type="text" name="customer[address]" id="customer_address" class="form-control" value="{{ old('customer.address') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="mb-0">{{ __('Contract') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Container Size') }} *</label>
                                <select name="size_id" id="size_id" class="form-select" required>
                                    <option value="">{{ __('Choose size...') }}</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Container Price (excl. VAT)') }} *</label>
                                <input type="number" step="1" min="0" id="container_price" name="container_price" class="form-control" value="{{ old('container_price', 0) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Container Price (incl. VAT)') }}</label>
                                <input type="text" id="container_price_w_vat" class="form-control" value="" readonly>
                            </div>
                            <div class="col-md-2 d-none">
                                <label class="form-label">{{ __('Tax %') }} *</label>
                                <input type="number" step="0.01" min="0" id="tax_value" name="tax_value" class="form-control" value="{{ old('tax_value', 15) }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">{{ __('Monthly Dumping per Container') }}</label>
                                <input type="number" step="1" min="1" name="monthly_dumping_cont" class="form-control" value="{{ old('monthly_dumping_cont', 1) }}">
                            </div>
                            <div class="col-md-2 d-none">
                                <label class="form-label">{{ __('No. Containers') }}</label>
                                <input type="number" step="1" min="1" name="no_containers" class="form-control" value="{{ old('no_containers', 1) }}">
                            </div>
                            <div class="col-md-3 d-none">
                                <label class="form-label">{{ __('Additional Trip Cost') }}</label>
                                <input type="number" step="0.01" min="0" name="additional_trip_cost" class="form-control" value="{{ old('additional_trip_cost') }}">
                            </div>
                            <div class="col-md-3 d-none">
                                <label class="form-label">{{ __('Contract Period (months)') }}</label>
                                <input type="number" step="1" min="1" name="contract_period" class="form-control" value="{{ old('contract_period', 1) }}">
                            </div>
                            <div class="col-md-3 d-none">
                                <label class="form-label">{{ __('Start Date') }} *</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-3 d-none">
                                <label class="form-label">{{ __('End Date') }} *</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', now()->addMonth()->toDateString()) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="mb-0">{{ __('Deliver Container') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Available Container') }} *</label>
                                <select name="container_id" id="container_id" class="form-select" required>
                                    <option value="">{{ __('Choose container...') }}</option>
                                    @foreach($availableContainers as $container)
                                        <option value="{{ $container->id }}" data-size="{{ $container->size_id }}">
                                            {{ $container->code }} - {{ $container->size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('Filtered by size automatically') }}</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Driver') }} *</label>
                                <select name="deliver_id" class="form-select" required>
                                    <option value="">{{ __('Choose driver...') }}</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Delivery Date') }} *</label>
                                <input type="date" name="deliver_at" class="form-control" value="{{ old('deliver_at', now()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Expected Discharge Date') }} *</label>
                                <input type="date" name="expected_discharge_date" class="form-control" value="{{ old('expected_discharge_date', now()->addDays(7)->toDateString()) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('City') }} *</label>
                                <input type="text" name="fill_city" class="form-control" value="{{ old('fill_city') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Address') }} *</label>
                                <input type="text" name="fill_address" class="form-control" value="{{ old('fill_address') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="mb-0">{{ __('Payment') }} {{ __('optional') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Amount') }}</label>
                                <input type="number" step="0.01" min="0" name="payment[amount]" class="form-control" value="{{ old('payment.amount') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="method" class="form-label">{{ __('Payment Method') }}</label>
                                <select class="form-select" id="method" name="payment[method]">
                                    <option value="">{{ __('Choose') }}</option>
                                    <option value="cash">{{ __('Cash') }}</option>
                                    <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                    <option value="check">{{ __('Check') }}</option>
                                    <option value="credit_card">{{ __('Credit Card') }}</option>
                                    <option value="other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="payment[is_payed]" id="is_payed" checked value="1">
                                    <label class="form-check-label" for="is_payed">{{ __('Mark as paid') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="mb-0">{{ __('Receipt') }}  {{__('optional')}} </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="receipt[create]" id="create_receipt" value="1">
                                    <label class="form-check-label" for="create_receipt">{{ __('Create receipt now for this fill') }}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Due Date') }}</label>
                                <input type="date" name="receipt[due_date]" class="form-control" value="{{ old('receipt.due_date', now()->addDays(7)->toDateString()) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Notes') }}</label>
                                <input type="text" name="receipt[notes]" class="form-control" value="{{ old('receipt.notes') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-bolt"></i> {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// General AJAX form handler for forms with 'send-ajax' class
document.addEventListener('DOMContentLoaded', function () {
    // Translations
    const translations = {
        processing: {!! json_encode(__('Processing...')) !!},
        errorOccurred: {!! json_encode(__('An error occurred. Please try again.')) !!},
        noResults: {!! json_encode(__('No results')) !!}
    };
    // Handle all forms with 'send-ajax' class
    document.querySelectorAll('form.send-ajax').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
            const formData = new FormData(form);
            const action = form.getAttribute('action');
            const method = form.getAttribute('method') || 'POST';
            
            // Lock the submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + translations.processing;
            }
            
            // Clear previous errors
            clearFormErrors(form);
            
            // Send AJAX request
            fetch(action, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw { status: response.status, data: data };
                    });
                }
                return response.json();
            })
            .then(data => {
                // Unlock button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
                
                // Handle response
                if (data.errors) {
                    // Show validation errors
                    showFormErrors(form, data.errors);
                } else if (data.url) {
                    // If response has URL, navigate to it
                    if (data.url === 'reload' || data.url === window.location.href) {
                        window.location.reload();
                    } else {
                        window.location.href = data.url;
                    }
                } else {
                    // Success - clear form data
                    form.reset();
                    // Show success message if provided
                    if (data.message) {
                        showAlert('success', data.message);
                    }
                }
            })
            .catch(error => {
                // Unlock button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
                
                // Handle errors
                if (error.data && error.data.errors) {
                    showFormErrors(form, error.data.errors);
                } else if (error.data && error.data.message) {
                    showAlert('danger', error.data.message);
                } else {
                    showAlert('danger', translations.errorOccurred);
                }
            });
        });
    });
    
    // Function to clear form errors
    function clearFormErrors(form) {
        // Remove error classes from inputs
        form.querySelectorAll('.is-invalid').forEach(function(el) {
            el.classList.remove('is-invalid');
        });
        
        // Remove error messages
        form.querySelectorAll('.invalid-feedback').forEach(function(el) {
            el.remove();
        });
        
        // Remove general error alert
        const errorAlert = form.querySelector('.alert-danger');
        if (errorAlert) {
            errorAlert.remove();
        }
    }
    
    // Function to show form errors
    function showFormErrors(form, errors) {
        // Show field-specific errors
        Object.keys(errors).forEach(function(field) {
            const fieldName = field.replace(/\./g, '_');
            let input = form.querySelector('[name="' + field + '"]') || 
                       form.querySelector('[name="' + field + '[]"]') ||
                       form.querySelector('#' + fieldName) ||
                       form.querySelector('[name*="' + field + '"]');
            
            if (input) {
                input.classList.add('is-invalid');
                
                // Add error message
                let errorDiv = input.parentElement.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    input.parentElement.appendChild(errorDiv);
                }
                errorDiv.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
            }
        });
        
        // Show general errors if any
        if (errors.general || errors.message) {
            const errorMsg = errors.general || errors.message;
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger mt-3';
            alertDiv.textContent = Array.isArray(errorMsg) ? errorMsg[0] : errorMsg;
            form.insertBefore(alertDiv, form.firstChild);
        }
    }
    
    // Function to show alert (simple implementation)
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show';
        alertDiv.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            setTimeout(function() {
                alertDiv.remove();
            }, 5000);
        }
    }
    
    // Customer search functionality
    const searchInput = document.getElementById('customer_search');
    const results = document.getElementById('customer_results');
    const hidId = document.getElementById('customer_id');
    const nameEl = document.getElementById('customer_name');
    const mobileEl = document.getElementById('customer_mobile');
    const cityEl = document.getElementById('customer_city');
    const addressEl = document.getElementById('customer_address');
    const sizeSel = document.getElementById('size_id');
    const containerSel = document.getElementById('container_id');
    const priceEl = document.getElementById('container_price');
    const priceWVatEl = document.getElementById('container_price_w_vat');
    const taxEl = document.getElementById('tax_value');

    let timeout = null;
    function clearResults() {
        results.innerHTML = '';
        results.classList.add('d-none');
    }
    function showResults(items) {
        results.innerHTML = '';
        results.classList.remove('d-none');
        items.forEach(item => {
            const a = document.createElement('a');
            a.href = 'javascript:void(0)';
            a.className = 'list-group-item list-group-item-action';
            a.textContent = item.text;
            a.addEventListener('click', () => {
                hidId.value = item.id;
                nameEl.value = item.name || '';
                mobileEl.value = item.phone || '';
                cityEl.value = item.city || '';
                addressEl.value = item.address || '';
                clearResults();
            });
            results.appendChild(a);
        });
        if (items.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'list-group-item text-muted';
            empty.textContent = translations.noResults;
            results.appendChild(empty);
        }
    }
    searchInput && searchInput.addEventListener('input', function () {
        const term = this.value.trim();
        hidId.value = '';
        if (term.length < 2) { clearResults(); return; }
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetch(`{{ route('customers.search') }}?term=${encodeURIComponent(term)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => showResults(data.results || []))
            .catch(() => clearResults());
        }, 250);
    });
    document.addEventListener('click', function (e) {
        if (!results.contains(e.target) && e.target !== searchInput) {
            clearResults();
        }
    });

    function filterContainersBySize() {
        const sizeId = sizeSel.value;
        Array.from(containerSel.options).forEach(opt => {
            if (!opt.value) return;
            const ok = !sizeId || opt.getAttribute('data-size') === sizeId;
            opt.classList.toggle('d-none', !ok);
        });
        // if selected not matching, reset
        const selected = containerSel.selectedOptions[0];
        if (selected && selected.classList.contains('d-none')) {
            containerSel.value = '';
        }
    }
    if (sizeSel && containerSel) {
        sizeSel.addEventListener('change', filterContainersBySize);
        filterContainersBySize();
    }

    function updatePriceWithVat() {
        const base = parseFloat(priceEl?.value || '0') || 0;
        const tax = parseFloat(taxEl?.value || '0') || 0;
        const withVat = base * (1 + (tax / 100));
        if (priceWVatEl) priceWVatEl.value = withVat.toFixed(2);
    }
    priceEl && priceEl.addEventListener('input', updatePriceWithVat);
    taxEl && taxEl.addEventListener('input', updatePriceWithVat);
    updatePriceWithVat();
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection


