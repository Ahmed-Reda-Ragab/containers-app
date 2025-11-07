@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Container Fill Details') }} #{{ $contractContainerFill->no }}</h2>
                <div class="btn-group">
                    <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Contract') }}
                    </a>
                    <a href="{{ route('contract-container-fills.edit', $contractContainerFill) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Container Fill Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Container Number') }}:</th>
                                            <td><strong>#{{ $contractContainerFill->no }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Contract') }}:</th>
                                            <td>
                                                <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="text-decoration-none">
                                                    #{{ $contractContainerFill->contract->id }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Container') }}:</th>
                                            <td>{{ $contractContainerFill->container->code ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Container Type') }}:</th>
                                            <td>{{ $contractContainerFill->container->type->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Delivered By') }}:</th>
                                            <td>{{ $contractContainerFill->deliver->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Delivery Date') }}:</th>
                                            <td>{{ $contractContainerFill->deliver_at->format('Y-m-d') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">{{ __('Expected Discharge') }}:</th>
                                            <td>{{ $contractContainerFill->expected_discharge_date->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Discharge Date') }}:</th>
                                            <td>{{ $contractContainerFill->discharge_date ? $contractContainerFill->discharge_date->format('Y-m-d') : '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Discharged By') }}:</th>
                                            <td>{{ $contractContainerFill->discharge->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Client') }}:</th>
                                            <td>{{ $contractContainerFill->client->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('City') }}:</th>
                                            <td>{{ $contractContainerFill->city }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Address') }}:</th>
                                            <td>{{ $contractContainerFill->address }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($contractContainerFill->price)
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <strong>{{ __('Price') }}:</strong> {{ number_format($contractContainerFill->price, 2) }} {{ __('SAR') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($contractContainerFill->notes)
                                <div class="mt-4">
                                    <h6>{{ __('Notes') }}</h6>
                                    <div class="alert alert-light">
                                        {{ $contractContainerFill->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Status') }}</h6>
                        </div>
                        <div class="card-body text-center">
                            @if($contractContainerFill->is_discharged)
                                <div class="mb-3">
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                </div>
                                <h5 class="text-success">{{ __('Discharged') }}</h5>
                                <p class="text-muted">{{ __('Container has been discharged') }}</p>
                            @elseif($contractContainerFill->is_overdue)
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                                </div>
                                <h5 class="text-danger">{{ __('Overdue') }}</h5>
                                <p class="text-muted">{{ __('Container is overdue for discharge') }}</p>
                            @else
                                <div class="mb-3">
                                    <i class="fas fa-clock fa-3x text-warning"></i>
                                </div>
                                <h5 class="text-warning">{{ __('Active') }}</h5>
                                <p class="text-muted">{{ __('Container is currently active') }}</p>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted">
                                    {{ __('Expected Discharge') }}: {{ $contractContainerFill->expected_discharge_date->format('Y-m-d') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Actions') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('contract-container-fills.edit', $contractContainerFill) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> {{ __('Edit Container Fill') }}
                                </a>
                                <a href="{{ route('contracts.show', $contractContainerFill->contract) }}" class="btn btn-info">
                                    <i class="fas fa-file-contract"></i> {{ __('View Contract') }}
                                </a>
                                @if(!$contractContainerFill->is_discharged)
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dischargeModal" data-url="{{ route('contract-container-fills.discharge', $contractContainerFill) }}" data-no="{{ $contractContainerFill->no }}" data-user-role="{{ auth()->user()->role }}">
                                        <i class="fas fa-check"></i> {{ __('Discharge Container') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discharge Modal -->
@if(!$contractContainerFill->is_discharged)
    @include('components.discharge-modal')
@endif

@endsection


