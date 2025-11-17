@extends('layouts.master')

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
                        <td>
                            <span class="badge bg-{{ $offer->lifecycle_badge }}">{{ __(ucfirst(str_replace('_', ' ', $offer->lifecycle_status ?? 'active'))) }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a class="btn btn-outline-primary" href="{{ route('offers.show', $offer) }}" title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a class="btn btn-outline-secondary" href="{{ route('offers.edit', $offer) }}" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-outline-info" href="{{ route('offers.print', $offer) }}" target="_blank" title="{{ __('Print') }}">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('offers.destroy', $offer) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this offer?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @if($offer->lifecycle_status !== 'inactive')
                                    <form action="{{ route('offers.deactivate', $offer) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Deactivate this quotation?') }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-warning" title="{{ __('Deactivate') }}">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
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


