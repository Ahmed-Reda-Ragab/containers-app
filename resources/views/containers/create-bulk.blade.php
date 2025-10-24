@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('containers.create_bulk') }}</h4>
                    <a href="{{ route('containers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('containers.back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('containers.bulk_creation_description') }}
                    </div>

                    <form action="{{ route('containers.store-bulk') }}" method="POST" id="bulkForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="code_prefix" class="form-label">{{ __('containers.code_prefix') }} </label>
                                <input type="text" class="form-control @error('code_prefix') is-invalid @enderror"
                                       id="code_prefix" name="code_prefix" value="{{ old('code_prefix') }}"
                                       placeholder="{{ __('containers.enter_prefix') }}" >
                                @error('code_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="from_count" class="form-label">{{ __('From Count') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('count') is-invalid @enderror"
                                       id="from_count" name="from_count" value="{{ old('from_count', 1) }}" min="1" max="100"
                                       placeholder="{{ __('From Count') }}" required>
                                @error('from_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="count" class="form-label">{{ __('To Count') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('count') is-invalid @enderror"
                                       id="to_count" name="to_count" value="{{ old('to_count', 1) }}" min="1" max="100"
                                       placeholder="{{ __('To Count') }}" required>
                                @error('to_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="size_id" class="form-label">{{ __('containers.size') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('size_id') is-invalid @enderror"
                                        id="size_id" name="size_id" required>
                                    <option value="">{{ __('containers.select_size') }}</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('size_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">{{ __('containers.status') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    <option value="">{{ __('containers.select_status') }}</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('containers.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="{{ __('containers.enter_description') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview Section -->
                        <div class="card mb-3" id="previewSection" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('containers.generated_codes_preview') }}</h6>
                            </div>
                            <div class="card-body">
                                <div id="generatedCodes"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('containers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('containers.cancel') }}
                            </a>
                            <button type="button" class="btn btn-info" id="previewBtn">
                                <i class="fas fa-eye"></i> {{ __('containers.preview') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('containers.create_containers') }}
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
    const previewBtn = document.getElementById('previewBtn');
    const previewSection = document.getElementById('previewSection');
    const generatedCodes = document.getElementById('generatedCodes');
    const codePrefix = document.getElementById('code_prefix');
    const count = document.getElementById('count');

    previewBtn.addEventListener('click', function() {
        const prefix =  codePrefix.value.trim();
        const containerCount = parseInt(count.value) || 0;

        if (containerCount <= 0) {
            // if (!prefix || containerCount <= 0) {
            // alert('{{ __("Please enter a code prefix and count") }}');
            // return;
        }

        let codesHtml = '<div class="row">';
        for (let i = 1; i <= containerCount; i++) {
            const code = prefix + String(i);//.padStart(3, '-');
            codesHtml += `<div class="col-md-3 mb-2"><span class="badge bg-primary">${code}</span></div>`;
        }
        codesHtml += '</div>';

        generatedCodes.innerHTML = codesHtml;
        previewSection.style.display = 'block';
    });
});
</script>
@endsection
