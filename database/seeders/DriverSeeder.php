<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        if (Driver::count() > 0) return;

        Driver::insert([
            ['name' => 'Alice Chen',   'phone' => '555-1001', 'license_number' => 'D-1001', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bob Martin',   'phone' => '555-1002', 'license_number' => 'D-1002', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chris Davis',  'phone' => '555-1003', 'license_number' => 'D-1003', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
