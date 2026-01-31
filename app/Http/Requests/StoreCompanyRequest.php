<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-companies');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:companies,name',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'phone1' => 'nullable|string|max:30',
            'phone2' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address_line1' => 'nullable|string|max:100',
            'address_line2' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'primary_url' => 'nullable|url|max:255',
            'owner_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'type' => 'nullable|integer',
            'custom' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'num_of_licensed_users' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'name.unique' => 'A company with this name already exists.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo file size must not exceed 2MB.',
            'email.email' => 'Please provide a valid email address.',
            'primary_url.url' => 'Please provide a valid URL.',
            'owner_id.exists' => 'Selected owner does not exist.',
        ];
    }
}
