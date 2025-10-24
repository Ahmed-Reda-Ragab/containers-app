@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('Offer Details') }} #{{ $offer->id }}</h2>
        <div>
            <a href="{{ route('offers.edit', $offer) }}" class="btn btn-primary">{{ __('Edit') }}</a>
            <a href="{{ route('contracts.create.by-type', ['type' => 'business', 'offer_id' => $offer->id]) }}" class="btn btn-success">{{ __('Convert to Contract') }}</a>
            <a href="{{ route('offers.index') }}" class="btn btn-secondary">{{ __('Back to Offers') }}</a>
        </div>
    </div>

    @include('offers.partials.details')
</div>
@endsection


