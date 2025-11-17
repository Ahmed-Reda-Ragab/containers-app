<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndividualContainerRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'container_size_id' => 'required|exists:sizes,id',
            'container_id' => 'nullable|exists:containers,id',
            'container_number' => 'nullable|string|max:50',
            'location_city' => 'required|string|max:120',
            'location_district' => 'nullable|string|max:120',
            'location_landmark' => 'nullable|string|max:255',
            'delivery_date' => 'required|date',
            'delivery_days' => 'required|integer|min:1|max:60',
            'rental_count' => 'nullable|integer|min:1|max:24',
            'price_before_tax' => 'required|numeric|min:0',
            'delivery_driver_id' => 'nullable|exists:users,id',
            'delivery_truck_id' => 'nullable|exists:cars,id',
            'status' => 'nullable|in:active,completed,cancelled,overdue',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}

