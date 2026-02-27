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
            'duration' => 'required|numeric|min:0',
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
            'task_sprint'=> 'nullable|boolean',
            'flagged'    => 'nullable|boolean',

            // Task Team — assigned in same PUT as update
            'team_members'   => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'team_hours'     => 'nullable|array',
            'team_hours.*'   => 'numeric|min:0',
            'owner_hours'    => 'nullable|numeric|min:0',
            'split_evenly'   => 'nullable|boolean',
            'total_hours'    => 'nullable|numeric|min:0',
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
        $defaults = [
            'last_edited'    => now(),
            'last_edited_by' => auth()->id(),
        ];

        // Ensure duration is never null (DB column is NOT NULL)
        if (!$this->has('duration') || $this->duration === null || $this->duration === '') {
            $defaults['duration'] = 0;
        }

        // A child of a sprint task cannot itself be a sprint
        if ($this->filled('parent_id')) {
            $parent = \App\Models\Task::find($this->input('parent_id'));
            if ($parent && $parent->task_sprint) {
                $defaults['task_sprint'] = 0;
            }
        }

        // Ensure budget columns are never null — DB has NOT NULL constraint
        if (!$this->filled('target_budget')) $defaults['target_budget'] = 0;
        if (!$this->filled('actual_budget')) $defaults['actual_budget'] = 0;

        $this->merge($defaults);
    }
}
