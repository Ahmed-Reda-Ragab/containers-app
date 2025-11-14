<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->byType(UserType::from($request->user_type));
        }

        $users = $query->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ]);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userTypes = UserType::options();
        $roles = Role::all();
        return view('users.create', compact('userTypes', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();
        
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        $roleId = $data['role'] ?? null;
        unset($data['role']);

        $user = User::create($data);
        
        // Assign role if provided
        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $user->assignRole($role);
            }
        }

        return redirect()->route('users.index')
            ->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $userTypes = UserType::options();
        $roles = Role::all();
        $user->load('roles');
        return view('users.edit', compact('user', 'userTypes', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        
        // Add user ID to data for validation
        $data['user_id'] = $user->id;
        
        // Only update password if provided
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        $roleId = $data['role'] ?? null;
        unset($data['role']);

        $user->update($data);
        
        // Sync role if provided
        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $user->syncRoles([$role]);
            }
        } else {
            // If no role selected, remove all roles
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('User deleted successfully.'));
    }

    /**
     * Search users for AJAX requests (for select dropdowns)
     */
    public function search(Request $request)
    {
        $query = User::query();

        // Filter by user type if provided
        if ($request->filled('user_type')) {
            $query->byType(UserType::from($request->user_type));
        }

        // Search by name, phone, or email
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $users = $query->limit(20)->get(['id', 'name', 'phone', 'user_type']);

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'user_type' => $user->user_type->value,
                    'user_type_label' => $user->user_type_label,
                ];
            })
        ]);
    }
}
