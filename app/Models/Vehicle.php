<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'license_number',
        'driver_name',
        'driver_phone_number',
        'shop_entry_time',
    ];

    protected $casts = [
    'shop_entry_time' => 'datetime', 
    ];
}