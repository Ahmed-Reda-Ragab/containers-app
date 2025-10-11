@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 class="card-title">{{ __('Welcome to Container Management System') }}</h5>
                    <p class="card-text">{{ __('You are logged in! Start managing your containers.') }}</p>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('containers.index') }}" class="btn btn-primary">
                            <i class="fas fa-box-open"></i> {{ __('Manage Containers') }}
                        </a>
                        <a href="{{ route('containers.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> {{ __('Add New Container') }}
                        </a>
                        <a href="{{ route('contracts.index') }}" class="btn btn-info">
                            <i class="fas fa-file-contract"></i> {{ __('Manage Contracts') }}
                        </a>
                        <a href="{{ route('contracts.create') }}" class="btn btn-warning">
                            <i class="fas fa-plus"></i> {{ __('Create Contract') }}
                        </a>
                        <!-- <a href="#" class="btn btn-secondary">
                            <i class="fas fa-file-invoice"></i> {{ __('Manage Offers') }}
                        </a> -->
                        <!-- <a href="#" class="btn btn-dark">
                            <i class="fas fa-plus"></i> {{ __('Create Offer') }}
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
