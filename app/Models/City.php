<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function vehicles() { return $this->hasMany(Vehicle::class); }
    public function drivers()  { return $this->hasMany(Driver::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
}
