<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Gate::before() handles the super-admin bypass;
        // company-level users need the edit permission granted by their role.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * num_of_licensed_users is only included in the rule set when the
     * authenticated user is a system user (company_id = null).
     * This means the field is silently ignored for company-level users —
     * it never makes it into $validated, so there is nothing to strip later
     * (the controller still does an unset() as a belt-and-suspenders measure).
     */
    public function rules(): array
    {
        $companyId = $this->route('company')->id;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($companyId),
            ],
            'logo'             => 'nullable|image|max:2048',
            'phone1'           => 'nullable|string|max:30',
            'phone2'           => 'nullable|string|max:30',
            'fax'              => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:255',
            'address_line1'    => 'nullable|string|max:100',
            'address_line2'    => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:50',
            'state'            => 'nullable|string|max:50',
            'zip'              => 'nullable|string|max:20',
            'country'          => 'nullable|string|max:50',
            'primary_url'      => 'nullable|url|max:255',
            'owner_id'         => 'nullable|exists:users,id',
            'description'      => 'nullable|string',
            'type'             => 'nullable|integer',
            'custom'           => 'nullable|string|max:255',
            'category'         => 'nullable|string|max:255',
        ];

        // Only system users (company_id = null) may set the license cap
        if ($this->user()->company_id === null) {
            $rules['num_of_licensed_users'] = 'nullable|integer|min:1';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'             => 'Company name is required.',
            'name.unique'               => 'A company with this name already exists.',
            'logo.image'                => 'Logo must be an image file.',
            'logo.max'                  => 'Logo file size must not exceed 2MB.',
            'email.email'               => 'Please provide a valid email address.',
            'primary_url.url'           => 'Please provide a valid URL.',
            'owner_id.exists'           => 'Selected owner does not exist.',
            'num_of_licensed_users.min' => 'Licensed users must be at least 1.',
        ];
    }
}