<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentAssignRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'driver_id'   => ['required', 'exists:drivers,id'],
            'assigned_at' => ['nullable', 'date'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ];
    }
}
