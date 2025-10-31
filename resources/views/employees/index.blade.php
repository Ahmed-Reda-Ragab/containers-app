@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ __('Employees') }}</h2>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Add New Employee') }}
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

                
                @if($employees->count() > 0)
                        <x-datatable 
                            id="employeesTable"
                            :columns="[
                                ['title' => __('ID'), 'data' => 'id'],
                                ['title' => __('Employee Name'), 'data' => 'name'],
                                ['title' => __('Employee Job Code'), 'data' => 'job_code'],
                                ['title' => __('Employee Phone'), 'data' => 'phone'],
                                ['title' => __('Employee National ID'), 'data' => 'national_id'],
                                ['title' => __('Employee Status'), 'data' => 'status'],
                                ['title' => __('Created At'), 'data' => 'created_at'],
                                ['title' => __('Actions'), 'data' => 'actions']
                            ]"
                            :searchable="true"
                            :filterable="true"
                            :filters="[]"
                            :language="app()->getLocale()"
                        >
                            @foreach($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id }}</td>
                                    <td>
                                        <strong>{{ $employee->name }}</strong>
                                    </td>
                                    <td>{{ $employee->job_code }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ $employee->national_id }}</td>
                                    <td>
                                        {{ $employee->status }}
                                    </td>
                                    <td>{{ $employee->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-info" title="{{ __('employees.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-warning" title="{{ __('employees.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-datatable>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No Data found') }}</h5>
                            <p class="text-muted">{{ __('Get started by adding a new employee') }}</p>
                            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Add New Employee') }}
                            </a>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
