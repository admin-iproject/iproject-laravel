<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:tasks,id',
            
            // Dates & Duration
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|numeric|min:0',
            'duration_type' => 'nullable|integer|in:1,24',
            
            // Status & Progress
            'status' => 'required|integer|between:0,4',
            'percent_complete' => 'required|integer|between:0,100',
            'priority' => 'required|integer|between:0,10',
            'milestone' => 'nullable|boolean',
            
            // Task Properties
            'access' => 'required|integer|in:0,1',
            'related_url' => 'nullable|url|max:255',
            'notify' => 'nullable|boolean',
            'phase' => 'nullable|integer',
            'risk' => 'nullable|integer',
            
            // Financial
            'target_budget' => 'nullable|numeric|min:0',
            'cost_code' => 'nullable|string|max:20',
            'task_ignore_budget' => 'nullable|boolean',
            'type' => 'nullable|integer',
            
            // Additional
            'contact_id' => 'nullable|exists:contacts,id',
            'task_order' => 'nullable|integer',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'owner_id' => 'task owner',
            'parent_id' => 'parent task',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'percent_complete' => 'progress',
            'target_budget' => 'budget',
            'cost_code' => 'cost code',
            'related_url' => 'URL',
            'contact_id' => 'contact',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Task name is required.',
            'owner_id.required' => 'Please assign a task owner.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
            'percent_complete.between' => 'Progress must be between 0 and 100.',
            'priority.between' => 'Priority must be between 0 and 10.',
            'status.between' => 'Invalid task status.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set last_edited info
        $this->merge([
            'last_edited' => now(),
            'last_edited_by' => auth()->id(),
        ]);
    }
}
