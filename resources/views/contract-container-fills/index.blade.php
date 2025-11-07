@extends('layouts.master')

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
                <a href="{{ route('contract-container-fills.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Record Container Fill') }}
                </a>

                <a href="{{ route('contracts.create.by-type' , ['type' => 'individual']) }}" class="btn btn-info">
                    <i class="fas fa-file-contract"></i> {{ __('New Individual Contract') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    
                    <x-datatable-ajax
                            :url="url()->current()"
                            :columns="[
                            ['title' => '#', 'data' => 'name'],
                            ['title' => __('Contract'), 'data' => 'name'],
                            ['title' => __('Customer'), 'data' => 'name'],
                            ['title' => __('Container'), 'data' => 'name'],
                            ['title' => __('Delivered By'), 'data' => 'name'],
                            ['title' => __('Delivery Car'), 'data' => 'name'],
                            ['title' => __('Delivery Date'), 'data' => 'name'],
                            ['title' => __('Expected Discharge'), 'data' => 'name'],
                            ['title' => __('Discharge Date'), 'data' => 'name'],
                            ['title' => __('Discharged By'), 'data' => 'name'],
                            ['title' => __('Discharge Car'), 'data' => 'name'],
                            ['title' => __('Status'), 'data' => 'name'],
                            ['title' => __('Address'), 'data' => 'name'],
                            ['title' => __('Actions'), 'data' => 'actions'],
                            ]"
                            :dataColumns="[
                            ['data' => 'no'],
                            ['data' => 'contract'],
                            ['data' => 'customer'],
                            ['data' => 'container'],
                            ['data' => 'deliver'],
                            ['data' => 'deliver_car'],
                            ['data' => 'deliver_at'],
                            ['data' => 'expected_discharge_date'],
                            ['data' => 'discharge_date'],
                            ['data' => 'discharge'],
                            ['data' => 'discharge_car'],
                            ['data' => 'status'],
                            ['data' => 'location'],
                            ['data' => 'actions'],
                            ]" />
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
        responsive: true
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection

