@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ __('Offer Details') }} #{{ $offer->id }}</h2>
            <span class="badge bg-{{ $offer->lifecycle_badge }}">{{ ucfirst(str_replace('_', ' ', $offer->lifecycle_status)) }}</span>
        </div>
        <div class="btn-group">
            <a href="{{ route('offers.print', $offer) }}" target="_blank" class="btn btn-outline-info">
                <i class="fas fa-print me-1"></i> {{ __('Print') }}
            </a>
            <a href="{{ route('offers.edit', $offer) }}" class="btn btn-primary">{{ __('Edit') }}</a>
            <a href="{{ route('contracts.create.by-type', ['type' => 'business', 'offer_id' => $offer->id]) }}" class="btn btn-success">{{ __('Convert to Contract') }}</a>
            @if($offer->lifecycle_status !== 'inactive')
                <form action="{{ route('offers.deactivate', $offer) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Deactivate this quotation?') }}')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-warning">{{ __('Deactivate') }}</button>
                </form>
            @endif
            <a href="{{ route('offers.index') }}" class="btn btn-secondary">{{ __('Back to Offers') }}</a>
        </div>
    </div>

    @include('offers.partials.details')
</div>
@endsection


