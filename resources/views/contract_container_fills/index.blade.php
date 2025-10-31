@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Contract Container Fills</h4>
                    <a href="{{ route('contract-container-fills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Container Fill
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="containerFillsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Contract</th>
                                    <th>Container</th>
                                    <th>Client</th>
                                    <th>Deliver At</th>
                                    <th>Expected Discharge</th>
                                    <th>Discharge Date</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contractContainerFills as $fill)
                                    <tr>
                                        <td>{{ $fill->id }}</td>
                                        <td>
                                            @if($fill->contract)
                                                <a href="{{ route('contracts.show', $fill->contract) }}">
                                                    Contract #{{ $fill->contract->id }}
                                                </a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fill->container)
                                                {{ $fill->container->code }} ({{ $fill->container->type->name ?? '' }})
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $fill->client->name ?? '' }}</td>
                                        <td>{{ $fill->deliver_at->format('Y-m-d') }}</td>
                                        <td>{{ $fill->expected_discharge_date->format('Y-m-d') }}</td>
                                        <td>
                                            @if($fill->discharge_date)
                                                {{ $fill->discharge_date->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">Not discharged</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fill->price)
                                                ${{ number_format($fill->price, 2) }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('contract-container-fills.show', $fill) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contract-container-fills.edit', $fill) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('contract-container-fills.destroy', $fill) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this container fill?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No container fills found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($contractContainerFills->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $contractContainerFills->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#containerFillsTable').DataTable({
        "paging": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "language": {
            "search": "Search container fills:",
            "emptyTable": "No container fills found"
        }
    });
});
</script>
@endsection
