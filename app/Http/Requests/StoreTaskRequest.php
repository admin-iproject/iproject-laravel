<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            
            // Dates & Duration
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|numeric|min:0',
            'duration_type' => 'nullable|integer|in:1,24', // 1=hours, 24=days
            
            // Status & Progress
            'status' => 'nullable|integer|between:0,4',
            'percent_complete' => 'nullable|integer|between:0,100',
            'priority' => 'nullable|integer|between:0,10',
            'milestone' => 'nullable|integer|in:0,1', // Changed from boolean
            
            // Task Properties
            'access' => 'nullable|integer|in:0,1', // 0=public, 1=private
            'related_url' => 'nullable|url|max:255',
            'notify' => 'nullable|integer|in:0,1', // Changed from boolean
            'phase' => 'nullable|integer',
            'risk' => 'nullable|integer',
            
            // Financial
            'target_budget' => 'nullable|numeric|min:0',
            'cost_code' => 'nullable|string|max:20',
            'task_ignore_budget' => 'nullable|integer|in:0,1', // Changed from boolean
            'type' => 'nullable|integer',
            
            // Additional
            'contact_id' => 'nullable|exists:contacts,id',
            'task_order' => 'nullable|integer',
            
            // Task Team (optional during creation)
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'team_hours' => 'nullable|array',
            'team_hours.*' => 'numeric|min:0',
            'owner_hours' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'project_id' => 'project',
            'parent_id' => 'parent task',
            'owner_id' => 'task owner',
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
            'project_id.required' => 'Please select a project for this task.',
            'name.required' => 'Task name is required.',
            'owner_id.required' => 'Please assign a task owner.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
            'percent_complete.between' => 'Progress must be between 0 and 100.',
            'priority.between' => 'Priority must be between 0 and 10.',
            'status.between' => 'Invalid task status.',
            'duration_type.in' => 'Duration type must be hours (1) or days (24).',
            'access.in' => 'Task access must be public (0) or private (1).',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set defaults if not provided
        $defaults = [];

        if (!$this->has('status')) {
            $defaults['status'] = 0; // Not Started
        }

        if (!$this->has('percent_complete')) {
            $defaults['percent_complete'] = 0;
        }

        if (!$this->has('priority')) {
            $defaults['priority'] = 5; // Medium
        }

        if (!$this->has('access')) {
            $defaults['access'] = 0; // Public
        }

        if (!$this->has('duration_type')) {
            $defaults['duration_type'] = 1; // Hours
        }

        if (!$this->has('task_ignore_budget')) {
            $defaults['task_ignore_budget'] = false;
        }

        if (!$this->has('milestone')) {
            $defaults['milestone'] = 0;
        }

        if (!empty($defaults)) {
            $this->merge($defaults);
        }

        // Set creator_id
        $this->merge([
            'creator_id' => auth()->id(),
        ]);
    }
}
