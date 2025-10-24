@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                @isset($filled)
                <h2>{{ __('Filled Containers') }} ( {{ $fills->count() }} )</h2>
                @else
                <h2>{{ __('Container Fills') }}  </h2>
                @endif
                <!-- <a href="{{ route('contract-container-fills.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Record Container Fill') }}
                </a> -->
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="fillsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Contract') }}</th>
                                    <th>{{ __('Container') }}</th>
                                    <th>{{ __('Delivered By') }}</th>
                                    <th>{{ __('Delivery Car') }}</th>
                                    <th>{{ __('Delivery Date') }}</th>
                                    <th>{{ __('Expected Discharge') }}</th>
                                    <th>{{ __('Discharge Date') }}</th>
                                    <th>{{ __('Discharged By') }}</th>
                                    <th>{{ __('Discharge Car') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fills as $fill)
                                    <tr>
                                        <td><strong>#{{ $fill->no }}</strong></td>
                                        <td>
                                            <a href="{{ route('contracts.show', $fill->contract) }}" class="text-decoration-none">
                                                #{{ $fill->contract->id }}
                                            </a>
                                        </td>
                                        <td>{{ $fill->container->code ?? 'N/A' }}</td>
                                        <td>{{ $fill->deliver->name ?? 'N/A' }}</td>
                                        <td>{{ $fill->deliverCar->number ?? 'N/A' }}</td>
                                        <td>{{ $fill->deliver_at->format('Y-m-d') }}</td>
                                        <td>{{ $fill->expected_discharge_date->format('Y-m-d') }}</td>
                                        <td>{{ $fill->discharge_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>{{ $fill->discharge?->name ?? 'N/A' }}</td>
                                        <td>{{ $fill->dischargeCar->number ?? 'N/A' }}</td>
                                        <td>
                                            @if($fill->is_discharged)
                                                <span class="badge bg-success">{{ __('Discharged') }}</span>
                                            @elseif($fill->is_overdue)
                                                <span class="badge bg-danger">{{ __('Overdue') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('Active') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('contract-container-fills.show', $fill) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="{{ __('View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(!$fill->is_discharged)
                                                 
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#dischargeModal"
                                                        data-url="{{ route('contract-container-fills.discharge', $fill) }}"
                                                        >
                                                        <i class="fas fa-dolly"></i> {{ __('Discharge') }}
                                                    </button>
                                                @endif

                                                {{-- 
                                                <a href="{{ route('contract-container-fills.edit', $fill) }}" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   title="{{ __('Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('contract-container-fills.destroy', $fill) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this container fill?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="{{ __('Delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">{{ __('No container fills found.') }}</p>
                                                <a href="{{ route('contract-container-fills.create') }}" class="btn btn-primary">
                                                    {{ __('Record your first container fill') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($fills->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $fills->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.discharge-modal')

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#fillsTable').DataTable({
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

