<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_number'       => ['sometimes', 'required', 'string', 'max:50'],
            'driver_name'          => ['nullable', 'string', 'max:100'],
            'driver_phone_number'  => ['nullable', 'string', 'max:25'],
            'shop_entry_date'      => ['nullable', 'date'],
            'shop_entry_hour'      => ['nullable', 'integer', 'min:0', 'max:23'],
            'shop_entry_minute'    => ['nullable', 'integer', 'min:0', 'max:59'],
        ];
    }

    public function toVehicleData(): array
    {
        $data = $this->validated();

        if (
            array_key_exists('shop_entry_date', $data) ||
            array_key_exists('shop_entry_hour', $data) ||
            array_key_exists('shop_entry_minute', $data)
        ) {
            if (!empty($data['shop_entry_date'])) {
                $h = (int)($data['shop_entry_hour'] ?? 0);
                $m = (int)($data['shop_entry_minute'] ?? 0);
                $data['shop_entry_time'] = $data['shop_entry_date'].' '.sprintf('%02d:%02d:00', $h, $m);
            } else {
                $data['shop_entry_time'] = null;
            }
            unset($data['shop_entry_date'], $data['shop_entry_hour'], $data['shop_entry_minute']);
        }

        return $data;
    }
}
