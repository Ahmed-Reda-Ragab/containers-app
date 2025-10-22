@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ __('Contracts') }} @isset($type)<small class="text-muted">- {{ ucfirst($type) }}</small>@endisset</h2>
        <div>
            <a href="{{ route('contracts.create.by-type', ['type' => 'business']) }}" class="btn btn-primary">{{ __('New Business Contract') }}</a>
            <a href="{{ route('contracts.create.by-type', ['type' => 'individual']) }}" class="btn btn-outline-primary">{{ __('New Individual Contract') }}</a>
        </div>
    </div>

    <div class="mb-3">
        <a href="{{ route('contracts.index.by-type', ['type' => 'business']) }}" class="btn btn-sm btn-secondary">{{ __('Business') }}</a>
        <a href="{{ route('contracts.index.by-type', ['type' => 'individual']) }}" class="btn btn-sm btn-secondary">{{ __('Individual') }}</a>
        <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-light">{{ __('All') }}</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Number') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Containers') }}</th>
                        <th>{{ __('Period') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($contracts as $contract)
                    <tr>
                        <td>{{ $contract->number ?? ('#'.$contract->id) }}</td>
                        <td>{{ $contract->customer['name'] ?? 'N/A' }}</td>
                        <td>{{ ucfirst($contract->customer['type'] ?? 'business') }}</td>
                        <td>{{ $contract->no_containers }}</td>
                        <td>{{ $contract->contract_period }}</td>
                        <td><span class="badge bg-{{ $contract->status_badge }}">{{ ucfirst($contract->status) }}</span></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('contracts.show', $contract) }}">{{ __('View') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">{{ __('No contracts found.') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $contracts->links() }}</div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#contractsTable').DataTable({
        responsive: true,
        language: {
            @if(app()->getLocale() === 'ar')
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            @endif
        }
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
