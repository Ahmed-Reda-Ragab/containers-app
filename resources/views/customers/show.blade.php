@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('customers.show') }}: {{ $customer->name }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('customers.edit') }}
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('customers.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">{{ __('customers.customer_information') }}</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('customers.name') }}:</label>
                                <p class="form-control-plaintext">{{ $customer->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('customers.type') }}:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge {{ $customer->type === 'company' ? 'bg-info' : 'bg-success' }}">
                                        {{ ucfirst($customer->type) }}
                                    </span>
                                </p>
                            </div>

                            @if($customer->company_name)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.company_name') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->company_name }}</p>
                                </div>
                            @endif

                            @if($customer->type === 'company')
                                @if($customer->tax_number)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('customers.tax_number') }}:</label>
                                        <p class="form-control-plaintext">{{ $customer->tax_number }}</p>
                                    </div>
                                @endif

                                @if($customer->commercial_number)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('customers.commercial_number') }}:</label>
                                        <p class="form-control-plaintext">{{ $customer->commercial_number }}</p>
                                    </div>
                                @endif
                            @endif

                            @if($customer->contact_person && isset($customer->contact_person['phone']))
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.contact_person') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->contact_person['name']??'' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.contact_person') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->contact_person['phone']??'' }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">{{ __('customers.contact_information') }}</h5>
                            
                            @if($customer->phone)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.phone') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->phone }}</p>
                                </div>
                            @endif

                            @if($customer->email)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.email') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->email }}</p>
                                </div>
                            @endif

                            @if($customer->mobile)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.mobile') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->mobile }}</p>
                                </div>
                            @endif

                            @if($customer->telephone)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.telephone') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->telephone }}</p>
                                </div>
                            @endif

                            @if($customer->ext)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.ext') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->ext }}</p>
                                </div>
                            @endif

                            @if($customer->fax)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.fax') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->fax }}</p>
                                </div>
                            @endif

                            @if($customer->city)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('customers.city') }}:</label>
                                    <p class="form-control-plaintext">{{ $customer->city }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Address -->
                    @if($customer->address)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('customers.address') }}</h5>
                                <p class="form-control-plaintext">{{ $customer->address }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($customer->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('customers.notes') }}</h5>
                                <p class="form-control-plaintext">{{ $customer->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Contracts Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('customers.contracts') }}</h5>
                            @if($customer->contracts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('customers.contract_id') }}</th>
                                                <th>{{ __('customers.start_date') }}</th>
                                                <th>{{ __('customers.end_date') }}</th>
                                                <th>{{ __('customers.status') }}</th>
                                                <th>{{ __('customers.total_price') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->contracts as $contract)
                                                <tr>
                                                    <td><a href="{{ route('contracts.show', $contract) }}">#{{ $contract->id }}</a></td>
                                                    <td>{{ $contract->start_date ? $contract->start_date->format('M d, Y') : '' }}</td>
                                                    <td>{{ $contract->end_date ? $contract->end_date->format('M d, Y') : '' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $contract->status_badge }}">
                                                            {{ ucfirst($contract->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($contract->total_price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">{{ __('customers.no_contracts') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
