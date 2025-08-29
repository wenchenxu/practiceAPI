<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        if (City::count() > 0) return;

        City::insert([
            ['name' => 'Shanghai', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beijing',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shenzhen', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guangzhou','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
