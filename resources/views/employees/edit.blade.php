@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Edit Employee') }}</h2>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Employees') }}
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

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row row-cols-md-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Employee Name') }} *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-md-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_code" class="form-label">{{ __('Employee Job Code') }} *</label>
                                    <input type="text" class="form-control @error('job_code') is-invalid @enderror"
                                        id="job_code" name="job_code" value="{{ old('job_code', $employee->job_code) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-md-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('Employee Phone') }} *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-md-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="national_id" class="form-label">{{ __('Employee National ID') }} *</label>
                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                                        id="national_id" name="national_id" value="{{ old('national_id', $employee->national_id) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-md-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('Employee Status') }} *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3">{{ old('description', $car->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('Update Car') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection