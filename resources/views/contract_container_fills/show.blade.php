@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Container Fill Details</h4>
                    <div class="btn-group" role="group">
                        <a href="{{ route('contract-container-fills.edit', $contractContainerFill) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('contract-container-fills.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Container Fills
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Container Fill Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $contractContainerFill->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Number:</strong></td>
                                    <td>{{ $contractContainerFill->no }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Deliver At:</strong></td>
                                    <td>{{ $contractContainerFill->deliver_at->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Expected Discharge:</strong></td>
                                    <td>{{ $contractContainerFill->expected_discharge_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discharge Date:</strong></td>
                                    <td>
                                        @if($contractContainerFill->discharge_date)
                                            {{ $contractContainerFill->discharge_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">Not discharged</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Price:</strong></td>
                                    <td>
                                        @if($contractContainerFill->price)
                                            ${{ number_format($contractContainerFill->price, 2) }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $contractContainerFill->city }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $contractContainerFill->address }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Related Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Contract:</strong></td>
                                    <td>
                                        @if($contractContainerFill->contract)
                                            <a href="{{ route('contracts.show', $contractContainerFill->contract) }}">
                                                Contract #{{ $contractContainerFill->contract->id }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Container:</strong></td>
                                    <td>
                                        @if($contractContainerFill->container)
                                            {{ $contractContainerFill->container->code }} ({{ $contractContainerFill->container->type->name ?? 'N/A' }})
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Client:</strong></td>
                                    <td>{{ $contractContainerFill->client->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Deliver:</strong></td>
                                    <td>{{ $contractContainerFill->deliver->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discharge By:</strong></td>
                                    <td>{{ $contractContainerFill->discharge->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $contractContainerFill->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated At:</strong></td>
                                    <td>{{ $contractContainerFill->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($contractContainerFill->notes)
                        <div class="mt-4">
                            <h6>Notes</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $contractContainerFill->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route('contract-container-fills.destroy', $contractContainerFill) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this container fill?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Container Fill
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
