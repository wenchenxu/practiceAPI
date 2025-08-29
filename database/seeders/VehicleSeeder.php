<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        if (Vehicle::count() > 0) return;

        Vehicle::insert([
            ['license_number' => 'ABC-123', 'created_at' => now(), 'updated_at' => now()],
            ['license_number' => 'XYZ-987', 'created_at' => now(), 'updated_at' => now()],
            ['license_number' => 'JKL-555', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
