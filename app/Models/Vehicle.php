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
    
    // --- ADD THE FOLLOWING TWO BLOCKS ---

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['shop_entry_time_local_string'];

    /**
     * Get the shop entry time formatted as a simple string in the app's timezone.
     *
     * @return string|null
     */
    public function getShopEntryTimeLocalStringAttribute(): ?string
    {
        // The 'shop_entry_time' attribute is already a Carbon instance in the correct timezone (Asia/Shanghai)
        // thanks to the $casts property and your app.php config. We just format it.
        return $this->shop_entry_time ? $this->shop_entry_time->format('Y-m-d H:i:s') : null;
    }
}