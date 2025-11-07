@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">

                    <h4 class="mb-0">Create New Container Fill</h4>
                </div>
                <div class="card-toolbar">

                    <a href="{{ route('contract-container-fills.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Container Fills
                    </a>
                </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('contract-container-fills.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contract_id" class="form-label">Contract <span class="text-danger">*</span></label>
                                <select class="form-select @error('contract_id') is-invalid @enderror"
                                        id="contract_id" name="contract_id" required>
                                    <option value="">Select Contract</option>
                                    @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                            Contract #{{ $contract->id }} - {{ $contract->customer->name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contract_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="container_id" class="form-label">Container <span class="text-danger">*</span></label>
                                <select class="form-select @error('container_id') is-invalid @enderror"
                                        id="container_id" name="container_id" required>
                                    <option value="">Select Container</option>
                                    @foreach($containers as $container)
                                        <option value="{{ $container->id }}" {{ old('container_id') == $container->id ? 'selected' : '' }}>
                                            {{ $container->code }}   ( {{ __('Size') }} : {{ $container->size->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('container_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="no" class="form-label">Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('no') is-invalid @enderror"
                                       id="no" name="no" value="{{ old('no') }}" required
                                       placeholder="Enter container number">
                                @error('no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                <select class="form-select @error('client_id') is-invalid @enderror"
                                        id="client_id" name="client_id" required>
                                    <option value="">Select Client</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('client_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}"
                                           placeholder="Enter price (optional)">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="deliver_id" class="form-label">Deliver</label>
                                <select class="form-select @error('deliver_id') is-invalid @enderror"
                                        id="deliver_id" name="deliver_id">
                                    <option value="">Select Deliver</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('deliver_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('deliver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="deliver_at" class="form-label">Deliver At <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('deliver_at') is-invalid @enderror"
                                       id="deliver_at" name="deliver_at" value="{{ old('deliver_at') }}" required>
                                @error('deliver_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="expected_discharge_date" class="form-label">Expected Discharge Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('expected_discharge_date') is-invalid @enderror"
                                       id="expected_discharge_date" name="expected_discharge_date" value="{{ old('expected_discharge_date') }}" required>
                                @error('expected_discharge_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="discharge_date" class="form-label">Discharge Date</label>
                                <input type="date" class="form-control @error('discharge_date') is-invalid @enderror"
                                       id="discharge_date" name="discharge_date" value="{{ old('discharge_date') }}">
                                @error('discharge_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="discharge_id" class="form-label">Discharge By</label>
                                <select class="form-select @error('discharge_id') is-invalid @enderror"
                                        id="discharge_id" name="discharge_id">
                                    <option value="">Select Discharge By</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('discharge_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('discharge_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city') }}" required
                                       placeholder="Enter city">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="3" required
                                          placeholder="Enter address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3"
                                          placeholder="Enter notes (optional)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('contract-container-fills.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Container Fill
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
@endsection
