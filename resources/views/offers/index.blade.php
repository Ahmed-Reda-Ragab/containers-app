@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('Offers') }}</h2>
        <a href="{{ route('offers.create') }}" class="btn btn-primary">{{ __('New Offer') }}</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Containers') }}</th>
                        <th>{{ __('Period') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($offers as $offer)
                    <tr>
                        <td>#{{ $offer->id }}</td>
                        <td>{{ $offer->customer['name'] ?? '' }}</td>
                        <td>{{ __(ucfirst($offer->customer['type'] ?? 'business')) }}</td>
                        <td>{{ $offer->no_containers }}</td>
                        <td>{{ $offer->contract_period }} {{ __('months') }}</td>
                        <td>{{ __(ucfirst($offer->status ?? 'draft')) }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('offers.show', $offer) }}">{{ __('View') }}</a>

                            <form action="{{ route('offers.destroy', $offer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">{{ __('No offers found.') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $offers->links() }}</div>
        </div>
    </div>
</div>
@endsection


