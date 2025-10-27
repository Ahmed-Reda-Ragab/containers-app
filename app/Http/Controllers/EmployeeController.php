<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'job_code' => 'required|string|max:255|unique:employees,job_code',
            'phone' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            // 'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data['status'] = 'active';
        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', __('Employee created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'job_code' => 'required|string|max:255|unique:employees,job_code,' . $employee->id,
            'phone' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', __('Car updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', __('Employee deleted successfully.'));
    }
}
