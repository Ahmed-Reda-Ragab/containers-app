@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Daily Bookings') }}</h2>
                <div>
                    <a href="{{ route('bookings.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> {{ __('New Booking') }}
                    </a>
                    <a href="{{ route('reports.daily-report') }}" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> {{ __('Daily Report') }}
                    </a>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('bookings.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="date" class="form-label">{{ __('Select Date') }}</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-search"></i> {{ __('Filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $bookingStats['total'] }}</h5>
                            <p class="card-text">{{ __('Total Bookings') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">{{ $bookingStats['pending'] }}</h5>
                            <p class="card-text">{{ __('Pending') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ $bookingStats['confirmed'] }}</h5>
                            <p class="card-text">{{ __('Confirmed') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $bookingStats['delivered'] }}</h5>
                            <p class="card-text">{{ __('Delivered') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-danger">{{ $bookingStats['cancelled'] }}</h5>
                            <p class="card-text">{{ __('Cancelled') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Bookings for') }} {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h5>
                </div>
                <div class="card-body">
                    @if($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('SN') }}</th>
                                        <th>{{ __('Container') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Address') }}</th>
                                        <th>{{ __('Driver') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $index => $booking)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $booking->container->code }}</strong><br>
                                            <small class="text-muted">{{ $booking->container->type->name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->customer_name }}</strong><br>
                                            <small class="text-muted">#{{ $booking->contract_id }}</small>
                                        </td>
                                        <td>{{ $booking->customer_phone }}</td>
                                        <td>
                                            {{ $booking->delivery_address }}<br>
                                            <small class="text-muted">{{ $booking->city }}</small>
                                        </td>
                                        <td>{{ $booking->driver->name ?? '-' }}</td>
                                        <td>
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
                                        </td>
                                        <td>
                                            @if($booking->price)
                                                {{ number_format($booking->price, 2) }} {{ __('SAR') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($booking->status === 'pending')
                                                    <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-info btn-sm" title="{{ __('Confirm') }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @elseif($booking->status === 'confirmed')
                                                    <form action="{{ route('bookings.deliver', $booking) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm" title="{{ __('Mark as Delivered') }}">
                                                            <i class="fas fa-truck"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($booking->status !== 'delivered')
                                                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="{{ __('Cancel') }}"
                                                                onclick="return confirm('{{ __('Are you sure you want to cancel this booking?') }}')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $bookings->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No bookings found for this date') }}</h5>
                            <p class="text-muted">{{ __('Try selecting a different date or create a new booking.') }}</p>
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Create New Booking') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table th {
    background-color: #376092 !important;
    color: white !important;
    font-weight: bold;
}

.card-body h5 {
    font-size: 1.5rem;
    font-weight: bold;
}
</style>
@endpush
@endsection


