<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'user:create 
        {username : Login name (unique)}
        {role : hq|city_manager}
        {--city= : City name (required if role=city_manager)} 
        {--password= : Plaintext password (omit to be prompted)}';

    protected $description = 'Create an HQ or City Manager account';

    public function handle(): int
    {
        $username = (string) $this->argument('username');
        $role     = (string) $this->argument('role');

        if (!in_array($role, ['hq', 'city_manager'], true)) {
            $this->error('role must be hq or city_manager');
            return self::FAILURE;
        }

        $cityId = null;
        if ($role === 'city_manager') {
            $cityName = (string) ($this->option('city') ?? '');
            if ($cityName === '') {
                $this->error('--city is required for city_manager');
                return self::FAILURE;
            }
            $city = City::where('name', $cityName)->first();
            if (!$city) {
                $this->error("City '{$cityName}' not found. Create it first.");
                return self::FAILURE;
            }
            $cityId = $city->id;
        }

        $password = (string) ($this->option('password') ?? '');
        if ($password === '') {
            $password = $this->secret('Set password (input hidden)');
            if (!$password) { $this->error('Password required'); return self::FAILURE; }
        }

        if (User::where('username', $username)->exists()) {
            $this->error("User '{$username}' already exists.");
            return self::FAILURE;
        }

        $user = User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'role'     => $role,
            'city_id'  => $cityId,
        ]);

        $this->info("Created user id={$user->id}, username={$user->username}, role={$user->role}, city_id=" . ($user->city_id ?? 'NULL'));
        return self::SUCCESS;
    }
}
