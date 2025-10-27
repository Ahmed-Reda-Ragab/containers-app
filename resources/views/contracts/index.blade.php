@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
    @if (isset($type) && $type == 'business')

        <h2>
            {{ __('Business Contracts') }}
            </h2>
        <div>
            <a href="{{ route('contracts.create.by-type', ['type' => 'business']) }}" class="btn btn-primary">{{ __('New Business Contract') }}</a>
        </div>
        @else
            <h2>
                {{ __('Individual Contracts') }}
            </h2>
            <div>
                <a href="{{ route('contracts.create.by-type', ['type' => 'individual']) }}" class="btn btn-primary">{{ __('New Individual Contract') }}</a>
            </div>
        @endif

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
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($contracts as $contract)
                    <tr>
                        <td>{{ $contract->number ?? ('#'.$contract->id) }}</td>
                        <td>{{ $contract->customer['name'] ?? '' }}</td>
                        <td>{{ ucfirst($contract->customer['type'] ?? 'business') }}</td>
                        <td>{{ $contract->no_containers }}</td>
                        <td>{{ $contract->contract_period }} {{ __('months') }}</td>
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
        responsive: true
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
