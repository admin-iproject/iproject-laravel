<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware/policies
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Required fields (company_id auto-assigned from user)
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
            
            // Optional fields
            'short_name' => 'nullable|string|max:10',
            'department_id' => 'nullable|exists:departments,id',
            'url' => 'nullable|url|max:255',
            
            // Dates
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date',
            
            // Status and progress
            'status' => 'nullable|integer|between:0,6',
            'percent_complete' => 'nullable|integer|between:0,100',
            'priority' => 'nullable|integer|between:0,10',
            
            // Boolean flags
            'active' => 'nullable|boolean',
            'private' => 'nullable|boolean',
            'task_dates' => 'nullable|boolean',
            
            // Budget
            'target_budget' => 'nullable|numeric|min:0',
            
            // Text fields
            'description' => 'nullable|string',
            'phases' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:255',
            'contract' => 'nullable|string|max:50',
            'additional_tasks' => 'nullable|string|max:255',
            
            // Color
            'color_identifier' => 'nullable|regex:/^[0-9A-Fa-f]{6}$/',
            
            // Numeric
            'allocation_alert_range' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.max' => 'Project name cannot exceed 255 characters.',
            'company_id.required' => 'Please select a company.',
            'company_id.exists' => 'The selected company does not exist.',
            'owner_id.required' => 'Please select a project owner.',
            'owner_id.exists' => 'The selected owner does not exist.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'url.url' => 'Please enter a valid URL.',
            'color_identifier.regex' => 'Color must be a valid 6-digit hex code.',
            'target_budget.min' => 'Target budget cannot be negative.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate short_name if not provided
        if (empty($this->short_name) && !empty($this->name)) {
            $this->merge([
                'short_name' => substr($this->name, 0, 10)
            ]);
        }

        // Set default values
        $this->merge([
            'active' => $this->boolean('active', true),
            'private' => $this->boolean('private', false),
            'task_dates' => $this->boolean('task_dates', false),
            'status' => $this->input('status', 0),
            'percent_complete' => $this->input('percent_complete', 0),
            'color_identifier' => $this->input('color_identifier', 'eeeeee'),
        ]);
    }
}
