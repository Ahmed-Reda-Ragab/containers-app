@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('User Details') }}: {{ $user->name }}</h2>
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Users') }}
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Name') }}</label>
                                        <p class="form-control-plaintext">{{ $user->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Phone') }}</label>
                                        <p class="form-control-plaintext">{{ $user->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Email') }}</label>
                                        <p class="form-control-plaintext">{{ $user->email ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('User Type') }}</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-{{ $user->user_type_color }}">
                                                {{ $user->user_type_label }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Created At') }}</label>
                                        <p class="form-control-plaintext">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">{{ __('Updated At') }}</label>
                                        <p class="form-control-plaintext">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('User Statistics') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Total Contracts') }}</label>
                                <p class="form-control-plaintext">{{ $user->contracts->count() }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Total Payments') }}</label>
                                <p class="form-control-plaintext">{{ $user->payments->count() }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Delivered Containers') }}</label>
                                <p class="form-control-plaintext">{{ $user->deliveredContainers->count() }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Discharged Containers') }}</label>
                                <p class="form-control-plaintext">{{ $user->dischargedContainers->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ __('Actions') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> {{ __('Edit User') }}
                                </a>
                                
                                <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> {{ __('Delete User') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
