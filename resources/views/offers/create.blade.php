@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('Create Offer') }}</h2>
        <a href="{{ route('offers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to Offers') }}
        </a>
    </div>

    @include('offers.partials.form', ['action' => route('offers.store'), 'method' => 'POST'])
</div>
@endsection


