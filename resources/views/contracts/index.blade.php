@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Contracts') }}</h2>
                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Create Contract') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="contractsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Contract #') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Total Price') }}</th>
                                    <th>{{ __('Paid') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr>
                                        <td>
                                            <strong>#{{ $contract->id }}</strong>
                                        </td>
                                        <td>
                                            @if($contract->customer)
                                                {{ $contract->customer['name'] ?? 'N/A' }}
                                            @else
                                                {{ $contract->customer['name'] ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $contract->type->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <strong>{{ number_format($contract->total_price, 2) }} {{ __('SAR') }}</strong>
                                        </td>
                                        <td>
                                            {{ number_format($contract->total_payed, 2) }} {{ __('SAR') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $contract->status_badge }}">
                                                {{ ucfirst($contract->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $contract->start_date->format('Y-m-d') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('contracts.show', $contract) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                {{-- 
                                                <a href="{{ route('contracts.edit', $contract) }}" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   title="{{ __('Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('contracts.destroy', $contract) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this contract?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="{{ __('Delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>--}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">{{ __('No contracts found.') }}</p>
                                                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                                                    {{ __('Create your first contract') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($contracts->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $contracts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection

