@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('Edit Offer') }} #{{ $offer->id }}</h2>
        <a href="{{ route('offers.show', $offer) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to Offer') }}
        </a>
    </div>

    @include('offers.partials.form', ['action' => route('offers.update', $offer), 'method' => 'PUT'])
</div>
@endsection


