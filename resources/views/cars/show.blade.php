@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Car Details') }}</h2>
                <div>
                    <a href="{{ route('cars.edit', $car) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Cars') }}
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>{{ __('ID') }}:</th>
                                    <td>{{ $car->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Number') }}:</th>
                                    <td>{{ $car->number }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Description') }}:</th>
                                    <td>{{ $car->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created At') }}:</th>
                                    <td>{{ $car->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Updated At') }}:</th>
                                    <td>{{ $car->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
