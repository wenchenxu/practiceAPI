<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:25'],
            'license_number' => ['required', 'string', 'max:50', 'unique:drivers,license_number'],
            'license_expiry' => ['nullable', 'date'],
            'status'         => ['nullable', 'string', 'in:active,inactive,suspended'],
        ];
    }
}
