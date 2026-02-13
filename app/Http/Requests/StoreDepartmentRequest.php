<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:255',
            'owner_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required.',
            'name.max' => 'Department name cannot exceed 255 characters.',
            'parent_id.exists' => 'Selected parent department does not exist.',
            'owner_id.exists' => 'Selected owner does not exist.',
            'url.url' => 'Please provide a valid URL.',
        ];
    }
}
