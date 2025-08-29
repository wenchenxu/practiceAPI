<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_number',
        'driver_name',
        'driver_phone_number',
        'shop_entry_time',
    ];

    protected $casts = [
        'shop_entry_time' => 'datetime',
    ];

    protected $appends = ['shop_entry_time_local_string'];

    public function getShopEntryTimeLocalStringAttribute(): ?string
    {
        return $this->shop_entry_time
            ? $this->shop_entry_time->format('Y-m-d H:i:s')
            : null;
    }

    // Relationships
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'assignments')
            ->withPivot(['assigned_at', 'released_at', 'notes'])
            ->withTimestamps();
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)
            ->whereNull('released_at')
            ->latestOfMany('assigned_at');
    }

    // Domain behavior
    public function assignTo(Driver $driver, ?Carbon $when = null, ?string $notes = null): Assignment
    {
        if ($this->currentAssignment()->exists()) {
            throw new \RuntimeException('Vehicle already has an active assignment.');
        }
        if ($driver->currentAssignment()->exists()) {
            throw new \RuntimeException('Driver already has an active vehicle.');
        }

        return $this->assignments()->create([
            'driver_id'   => $driver->id,
            'assigned_at' => $when?->toDateTimeString() ?? now(),
            'notes'       => $notes,
        ]);
    }

    public function release(?Carbon $when = null): void
    {
        $active = $this->currentAssignment()->first();
        if (!$active) {
            return;
        }
        $active->update([
            'released_at' => $when?->toDateTimeString() ?? now(),
        ]);
    }
}
