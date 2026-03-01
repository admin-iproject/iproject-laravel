<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:100',
            'type'         => 'nullable|string|max:50',
            'description'  => 'nullable|string|max:1000',
            'parent_id'    => 'nullable|exists:departments,id',
            'owner_id'     => 'nullable|exists:users,id',
            'phone'        => 'nullable|string|max:30',
            'fax'          => 'nullable|string|max:30',
            'url'          => 'nullable|url|max:255',
            'address_line1'=> 'nullable|string|max:100',
            'address_line2'=> 'nullable|string|max:100',
            'city'         => 'nullable|string|max:50',
            'state'        => 'nullable|string|max:50',
            'zip'          => 'nullable|string|max:20',
            'country'      => 'nullable|string|max:50',
            'lat'          => 'nullable|numeric',
            'lng'          => 'nullable|numeric',
        ];
    }
}
