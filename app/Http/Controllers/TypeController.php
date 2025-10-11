<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return response()->json($types);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types,name',
        ]);

        $type = Type::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => __('Type created successfully'),
            'type' => $type
        ]);
    }

    public function update(Request $request, Type $type): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types,name,' . $type->id,
        ]);

        $type->update($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => __('Type updated successfully'),
            'type' => $type
        ]);
    }

    public function destroy(Type $type): JsonResponse
    {
        // Check if type is being used by any containers
        if ($type->containers()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => __('Cannot delete type that is being used by containers')
            ], 422);
        }

        $type->delete();

        return response()->json([
            'success' => true,
            'message' => __('Type deleted successfully')
        ]);
    }
}
