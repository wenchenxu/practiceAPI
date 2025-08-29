<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // TODO: lock this down later with policies. For now, allow.
        return true;
    }

    public function rules(): array
    {
        return [
            'license_number'       => ['required', 'string', 'max:50'],
            'driver_name'          => ['nullable', 'string', 'max:100'],
            'driver_phone_number'  => ['nullable', 'string', 'max:25'],
            'shop_entry_date'      => ['nullable', 'date'],
            'shop_entry_hour'      => ['nullable', 'integer', 'min:0', 'max:23'],
            'shop_entry_minute'    => ['nullable', 'integer', 'min:0', 'max:59'],
        ];
    }

    /**
     * Return a normalized payload for creating a Vehicle.
     * Combines date/hour/minute into 'shop_entry_time' (or null).
     */
    public function toVehicleData(): array
    {
        $data = $this->validated();

        $data['shop_entry_time'] = null;
        if (!empty($data['shop_entry_date'])) {
            $h = (int)($data['shop_entry_hour'] ?? 0);
            $m = (int)($data['shop_entry_minute'] ?? 0);
            // Use app timezone; controller/model will cast to Carbon
            $data['shop_entry_time'] = $data['shop_entry_date'].' '.sprintf('%02d:%02d:00', $h, $m);
        }

        // Remove the form-only fields
        unset($data['shop_entry_date'], $data['shop_entry_hour'], $data['shop_entry_minute']);

        return $data;
    }
}
