<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() > 0) return;

        $shanghai = City::where('name','Shanghai')->first();
        $beijing  = City::where('name','Beijing')->first();

        User::create([
            'username' => 'hq',
            'password' => Hash::make('hq-secret'), // CHANGE THIS
            'role'     => 'hq',
            'city_id'  => null,
        ]);

        User::create([
            'username' => 'sh_manager',
            'password' => Hash::make('sh-secret'), // CHANGE THIS
            'role'     => 'city_manager',
            'city_id'  => $shanghai?->id,
        ]);

        User::create([
            'username' => 'bj_manager',
            'password' => Hash::make('bj-secret'), // CHANGE THIS
            'role'     => 'city_manager',
            'city_id'  => $beijing?->id,
        ]);
    }
}
