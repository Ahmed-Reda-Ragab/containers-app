@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('customers.edit') }}: {{ $customer->name }}</h4>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('customers.back') }}
                    </a>
                </div>

                <div class="card-body">
                <form action="{{ route('customers.update', $customer) }}" method="POST" id="customerForm">
    @csrf
    @method('PUT')

    <!-- Basic Information -->
    <div class="row row-cols-2">
        <div class="col-md-6">
            <label for="type" class="form-label">{{ __('customers.type') }} <span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="">{{ __('customers.select_type') }}</option>
                <option value="individual" {{ old('type', $customer->type) == 'individual' ? 'selected' : '' }}>
                    {{ __('customers.individual') }}
                </option>
                <option value="company" {{ old('type', $customer->type) == 'company' ? 'selected' : '' }}>
                    {{ __('customers.company') }}
                </option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">{{ __('customers.name') }} / {{ __('customers.company_name') }}
                <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $customer->name) }}"
                   placeholder="{{ __('customers.enter_name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3 d-none">
            <label for="company_name" class="form-label">{{ __('customers.company_name') }}</label>
            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                   id="company_name" name="company_name" value="{{ old('company_name', $customer->company_name) }}"
                   placeholder="{{ __('customers.enter_company_name') }}">
            @error('company_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">{{ __('customers.phone') }}</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                   id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                   placeholder="{{ __('customers.enter_phone') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">{{ __('customers.email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $customer->email) }}"
                   placeholder="{{ __('customers.enter_email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Contact Person (for companies) -->
        <div class="col-md-6 mb-3 is-company">
            <label for="contact_person" class="form-label">{{ __('customers.contact_person') }}</label>
            <input type="text" class="form-control @error('contact_person.name') is-invalid @enderror"
                   id="contact_person" name="contact_person[name]"
                   value="{{ old('contact_person.name', $customer->contact_person['name'] ?? '') }}"
                   placeholder="{{ __('customers.enter_contact_person') }}">
            @error('contact_person.name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3 is-company">
            <label for="contact_person_phone" class="form-label">{{ __('customers.telephone') }}</label>
            <input type="text" class="form-control @error('contact_person.phone') is-invalid @enderror"
                   id="contact_person_phone" name="contact_person[phone]"
                   value="{{ old('contact_person.phone', $customer->contact_person['phone'] ?? '') }}"
                   placeholder="{{ __('customers.enter_telephone') }}">
            @error('contact_person.phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Company Information (shown only for company type) -->
    <div id="companyFields" style="{{ old('type', $customer->type) == 'company' ? '' : 'display: none;' }}">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tax_number" class="form-label">{{ __('customers.tax_number') }}
                    <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                       id="tax_number" name="tax_number"
                       value="{{ old('tax_number', $customer->tax_number) }}"
                       placeholder="{{ __('customers.enter_tax_number') }}">
                @error('tax_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="commercial_number" class="form-label">{{ __('customers.commercial_number') }}
                    <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('commercial_number') is-invalid @enderror"
                       id="commercial_number" name="commercial_number"
                       value="{{ old('commercial_number', $customer->commercial_number) }}"
                       placeholder="{{ __('customers.enter_commercial_number') }}">
                @error('commercial_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="city" class="form-label">{{ __('customers.city') }}</label>
        <input type="text" class="form-control @error('city') is-invalid @enderror"
               id="city" name="city" value="{{ old('city', $customer->city) }}"
               placeholder="{{ __('customers.enter_city') }}">
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Address -->
    <div class="mb-3">
        <label for="address" class="form-label">{{ __('customers.address') }}</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address" name="address" rows="3"
                  placeholder="{{ __('customers.enter_address') }}">{{ old('address', $customer->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('customers.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('customers.update_customer') }}
        </button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const companyFields = document.getElementById('companyFields');
    const taxNumberField = document.getElementById('tax_number');
    const commercialNumberField = document.getElementById('commercial_number');

    function toggleCompanyFields() {
        if (typeSelect.value === 'company') {
            companyFields.style.display = 'block';
            taxNumberField.required = true;
            commercialNumberField.required = true;
        } else {
            companyFields.style.display = 'none';
            taxNumberField.required = false;
            commercialNumberField.required = false;
        }
    }

    typeSelect.addEventListener('change', toggleCompanyFields);
    
    // Initialize on page load
    toggleCompanyFields();
});
</script>
@endsection
