<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'user_type' => ['required', Rule::enum(UserType::class)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'exists:roles,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Name')]),
            'email.email' => __('validation.email', ['attribute' => __('Email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('Email')]),
            'phone.required' => __('validation.required', ['attribute' => __('Phone')]),
            'phone.unique' => __('validation.unique', ['attribute' => __('Phone')]),
            'user_type.required' => __('validation.required', ['attribute' => __('User Type')]),
            'user_type.enum' => __('validation.enum', ['attribute' => __('User Type')]),
            'password.min' => __('validation.min.string', ['attribute' => __('Password'), 'min' => 8]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('Password')]),
        ];
    }
}
