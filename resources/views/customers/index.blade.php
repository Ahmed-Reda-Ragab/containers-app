@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">

                        <h4 class="mb-0">{{ __('customers.title') }}</h4>
                    </div>
                    <div class="card-toolbar">

                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('customers.add_new_customer') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <x-datatable-ajax
                        id="customersTable"
                        :url="route('customers.index')"
                        :columns="[
                            ['title' => __('customers.name'), 'data' => 'name'],
                            ['title' => __('customers.type'), 'data' => 'type_badge'],
                            ['title' => __('customers.company_info'), 'data' => 'company_info'],
                            ['title' => __('customers.phone'), 'data' => 'phone'],
                            ['title' => __('customers.email'), 'data' => 'email'],
                            ['title' => __('customers.city'), 'data' => 'city'],
                            ['title' => __('customers.actions'), 'data' => 'actions']
                        ]"
                        :dataColumns="[
                            ['data' => 'name'],
                            ['data' => 'type_badge'],
                            ['data' => 'company_info'],
                            ['data' => 'phone'],
                            ['data' => 'email'],
                            ['data' => 'city'],
                            ['data' => 'actions'],
                        ]"
                        :searchable="true"
                        :filterable="true"
                        :filters="[
                            [
                                'name' => 'type',
                                'placeholder' => __('customers.filter_by_type'),
                                'column' => 1,
                                'options' => [
                                    'individual' => __('Individual'),
                                    'company' => __('Business')
                                ]
                            ]
                        ]"
                        :language="app()->getLocale()" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection