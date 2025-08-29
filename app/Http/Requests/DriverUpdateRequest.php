<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'           => ['sometimes', 'required', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:25'],
            // Keep simple for MVP: do not enforce unique here to avoid analyzer headaches.
            // The database unique index on 'license_number' will still protect integrity.
            'license_number' => ['sometimes', 'required', 'string', 'max:50'],
            'license_expiry' => ['nullable', 'date'],
            'status'         => ['nullable', 'string', 'in:active,inactive,suspended'],
        ];
    }
}
