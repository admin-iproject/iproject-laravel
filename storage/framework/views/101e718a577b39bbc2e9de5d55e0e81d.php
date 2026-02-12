<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'availability' => ['nullable', 'array'],
            'availability.*' => ['nullable', 'numeric', 'min:0', 'max:24'],
        ];
    }
}
 ?><?php /**PATH C:\iPROJECT\iproject-laravel-complete\iproject-laravel\resources\views/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>