@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Create Receipt from Container Fills') }} - {{ __('Contract') }} #{{ $contract->id }}</h2>
                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Contract') }}
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

            <!-- Contract Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">{{ __('Contract Information') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('Customer') }}:</strong> {{ $contract->customer['name'] ?? '' }}</p>
                            <p><strong>{{ __('Contract #') }}:</strong> #{{ $contract->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('Total Price') }}:</strong> {{ number_format($contract->total_price, 2) }} {{ __('SAR') }}</p>
                            <p><strong>{{ __('Total Paid') }}:</strong> {{ number_format($contract->total_payed, 2) }} {{ __('SAR') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($contract->contractContainerFills->count() > 0)
            <form action="{{ route('receipts.store-from-fills', $contract) }}" method="POST" id="receiptForm">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">

                            <h5 class="mb-0">{{ __('Select Container Fills') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                            <label for="selectAll" class="form-check-label ms-2">{{ __('Select All') }}</label>
                                        </th>
                                        <th>{{ __('Container #') }}</th>
                                        <th>{{ __('Container') }}</th>
                                        <th>{{ __('Delivered By') }}</th>
                                        <th>{{ __('Delivery Date') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contract->contractContainerFills as $fill)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                name="contract_container_fill_ids[]"
                                                value="{{ $fill->id }}"
                                                class="form-check-input fill-checkbox"
                                                data-price="{{ $fill->price }}">
                                        </td>
                                        <td><strong>#{{ $fill->id }}</strong></td>
                                        <td>{{ $fill->container->code ?? '' }}</td>
                                        <td>{{ $fill->deliver->name ?? '' }}</td>
                                        <td>{{ $fill->deliver_at->format('Y-m-d') }}</td>
                                        <td><strong>{{ number_format($fill->price, 2) }} {{ __('SAR') }}</strong></td>
                                        <td>
                                            @if($fill->is_discharged)
                                            <span class="badge bg-success">{{ __('Discharged') }}</span>
                                            @elseif($fill->is_overdue)
                                            <span class="badge bg-danger">{{ __('Overdue') }}</span>
                                            @else
                                            <span class="badge bg-warning">{{ __('Active') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">

                            <h5 class="mb-0">{{ __('Receipt Details') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">{{ __('Due Date') }} *</label>
                                    <input type="date"
                                        class="form-control @error('due_date') is-invalid @enderror"
                                        id="due_date"
                                        name="due_date"
                                        value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                                        required>
                                    @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                        id="notes"
                                        name="notes"
                                        rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">

                            <h5 class="mb-0">{{ __('Receipt Summary') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <span>{{ __('Selected Items') }}:</span>
                                    <span id="selectedCount">0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>{{ __('Total Amount') }}:</span>
                                    <strong id="totalAmount">0.00 {{ __('SAR') }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    {{ __('The receipt will be created with the total amount of selected container fills.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="fas fa-receipt"></i> {{ __('Create Receipt') }}
                    </button>
                </div>
            </form>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No Available Container Fills') }}</h5>
                    <p class="text-muted">{{ __('All container fills for this contract are already included in receipts.') }}</p>
                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-primary">
                        {{ __('Back to Contract') }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Select all functionality
        $('#selectAll').change(function() {
            $('.fill-checkbox').prop('checked', this.checked);
            updateSummary();
        });

        // Individual checkbox change
        $('.fill-checkbox').change(function() {
            updateSummary();
            updateSelectAllState();
        });

        function updateSelectAllState() {
            const totalCheckboxes = $('.fill-checkbox').length;
            const checkedCheckboxes = $('.fill-checkbox:checked').length;

            if (checkedCheckboxes === 0) {
                $('#selectAll').prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAll').prop('indeterminate', false).prop('checked', true);
            } else {
                $('#selectAll').prop('indeterminate', true);
            }
        }

        function updateSummary() {
            let selectedCount = 0;
            let totalAmount = 0;

            $('.fill-checkbox:checked').each(function() {
                selectedCount++;
                totalAmount += parseFloat($(this).data('price'));
            });

            $('#selectedCount').text(selectedCount);
            $('#totalAmount').text(totalAmount.toFixed(2) + ' {{ __("SAR") }}');

            // Enable/disable submit button
            if (selectedCount > 0) {
                $('#submitBtn').prop('disabled', false);
            } else {
                $('#submitBtn').prop('disabled', true);
            }
        }

        // Form submission validation
        $('#receiptForm').submit(function(e) {
            const selectedCount = $('.fill-checkbox:checked').length;
            if (selectedCount === 0) {
                e.preventDefault();
                alert('{{ __("Please select at least one container fill.") }}');
                return false;
            }
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection