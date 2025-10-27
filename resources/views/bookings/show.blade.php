@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Booking Details') }}</h2>
                <div>
                    <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Bookings') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Booking Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Booking Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Booking Date') }}:</strong>
                                        <div>{{ $booking->booking_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Status') }}:</strong>
                                        <div>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="badge bg-info">{{ __('Confirmed') }}</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge bg-success">{{ __('Delivered') }}</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">{{ __('Cancelled') }}</span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Container') }}:</strong>
                                        <div>
                                            {{ $booking->container->code }} - {{ $booking->container->type->name ?? 'Unknown' }}
                                            <br><small class="text-muted">{{ $booking->container->size ?? '' }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Driver') }}:</strong>
                                        <div>{{ $booking->driver->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Customer Name') }}:</strong>
                                        <div>{{ $booking->customer_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Customer Phone') }}:</strong>
                                        <div>{{ $booking->customer_phone }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('Delivery Address') }}:</strong>
                                <div>{{ $booking->delivery_address }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('City') }}:</strong>
                                        <div>{{ $booking->city }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Price') }}:</strong>
                                        <div>
                                            @if($booking->price)
                                                {{ number_format($booking->price, 2) }} {{ __('SAR') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($booking->notes)
                                <div class="mb-3">
                                    <strong>{{ __('Notes') }}:</strong>
                                    <div>{{ $booking->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contract Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Contract Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Contract ID') }}:</strong>
                                        <div>#{{ $booking->contract_id }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>{{ __('Customer') }}:</strong>
                                        <div>{{ $booking->customer->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Actions') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm w-100">
                                        <i class="fas fa-check"></i> {{ __('Confirm Booking') }}
                                    </button>
                                </form>
                            @elseif($booking->status === 'confirmed')
                                <form action="{{ route('bookings.deliver', $booking) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fas fa-truck"></i> {{ __('Mark as Delivered') }}
                                    </button>
                                </form>
                            @endif

                            @if($booking->status !== 'delivered')
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm w-100"
                                            onclick="return confirm('{{ __('Are you sure you want to cancel this booking?') }}')">
                                        <i class="fas fa-times"></i> {{ __('Cancel Booking') }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-edit"></i> {{ __('Edit Booking') }}
                            </a>
                        </div>
                    </div>

                    <!-- Container Information -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Container Details') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('Container Code') }}:</strong>
                                <div>{{ $booking->container->code }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Type') }}:</strong>
                                <div>{{ $booking->container->type->name ?? 'Unknown' }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Size') }}:</strong>
                                <div>{{ $booking->container->size ?? '' }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('Status') }}:</strong>
                                <div>
                                    <span class="badge bg-{{ $booking->container->status->color() }}">
                                        {{ $booking->container->status->label() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection


