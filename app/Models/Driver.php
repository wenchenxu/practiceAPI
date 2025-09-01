<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'license_number',
        'license_expiry',
        'status',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    // Relationships
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'assignments')
            ->withPivot(['assigned_at', 'released_at', 'notes'])
            ->withTimestamps();
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)
            ->whereNull('released_at')
            ->latestOfMany('assigned_at');
    }
}
