@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
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
                    <div class="row g-4">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-light border-bottom-0">
                                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>{{ __('customers.customer_information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">{{ __('customers.name') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->name }}</dd>

                                        <dt class="col-sm-4">{{ __('customers.type') }}</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge {{ $customer->type === 'company' ? 'bg-info' : 'bg-success' }}">
                                                {{ ucfirst($customer->type) }}
                                            </span>
                                        </dd>

                                        @if($customer->company_name)
                                        <dt class="col-sm-4">{{ __('customers.company_name') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->company_name }}</dd>
                                        @endif

                                        @if($customer->type === 'company')
                                        @if($customer->tax_number)
                                        <dt class="col-sm-4">{{ __('customers.tax_number') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->tax_number }}</dd>
                                        @endif

                                        @if($customer->commercial_number)
                                        <dt class="col-sm-4">{{ __('customers.commercial_number') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->commercial_number }}</dd>
                                        @endif
                                        @endif

                                        @if($customer->contact_person && isset($customer->contact_person['phone']))
                                        <dt class="col-sm-4">{{ __('customers.contact_person') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->contact_person['name'] ?? '' }}</dd>

                                        <dt class="col-sm-4">{{ __('customers.contact_phone') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->contact_person['phone'] ?? '' }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-light border-bottom-0">
                                    <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>{{ __('customers.contact_information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        @if($customer->phone)
                                        <dt class="col-sm-4">{{ __('customers.phone') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->phone }}</dd>
                                        @endif

                                        @if($customer->email)
                                        <dt class="col-sm-4">{{ __('customers.email') }}</dt>
                                        <dd class="col-sm-8">
                                            <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                                {{ $customer->email }}
                                            </a>
                                        </dd>
                                        @endif

                                        @if($customer->mobile)
                                        <dt class="col-sm-4">{{ __('customers.mobile') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->mobile }}</dd>
                                        @endif

                                        @if($customer->telephone)
                                        <dt class="col-sm-4">{{ __('customers.telephone') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->telephone }}</dd>
                                        @endif

                                        @if($customer->ext)
                                        <dt class="col-sm-4">{{ __('customers.ext') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->ext }}</dd>
                                        @endif

                                        @if($customer->fax)
                                        <dt class="col-sm-4">{{ __('customers.fax') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->fax }}</dd>
                                        @endif

                                        @if($customer->city)
                                        <dt class="col-sm-4">{{ __('customers.city') }}</dt>
                                        <dd class="col-sm-8">{{ $customer->city }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    @if($customer->address)
                    <div class="card mt-4 shadow-sm border-0">
                        <div class="card-header bg-light border-bottom-0">
                            <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>{{ __('customers.address') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $customer->address }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($customer->notes)
                    <div class="card mt-4 shadow-sm border-0">
                        <div class="card-header bg-light border-bottom-0">
                            <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>{{ __('customers.notes') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 text-muted">{{ $customer->notes }}</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>


        <!-- Contracts Section -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">{{ __('customers.contracts') }}</h5>
                @if($customer->contracts->count() > 0)

                <x-datatable 
                            id="containersTable"
                            :columns="[
                                ['title' => __('Number'), 'data' => 'number'],
                                ['title' => __('Type'), 'data' => 'type'],
                                ['title' => __('Containers'), 'data' => 'size'],
                                ['title' => __('Period'), 'data' => 'status'],
                                ['title' => __('Created At'), 'data' => 'created_at'],
                                ['title' => __('Actions'), 'data' => 'description'],
                            ]"
                            :searchable="false"
                            :filterable="false"
                            :responsive="true"
                            :language="app()->getLocale()"
                        >
                        @foreach($customer->contracts as $contract)
                            <tr>
                                <td>{{ $contract->number ?? ('#'.$contract->id) }}</td>
                                <td>{{ ucfirst($contract->customer['type'] ?? 'business') }}</td>
                                <td>{{ $contract->no_containers }}</td>
                                <td>{{ $contract->contract_period }} {{ __('months') }}</td>
                                <td>{{ $contract->created_at }}</td>
                                
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('contracts.show', $contract) }}">{{ __('View') }}</a>
                                </td>
                            </tr>
                            @endforeach
                </x-datatable>
                @else
                <p class="text-muted">{{ __('customers.no_contracts') }}</p>
                @endif
            </div>
        </div>

    </div>

</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection