<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['username', 'password', 'role', 'city_id'];
    protected $hidden   = ['password'];

    public function city() { return $this->belongsTo(City::class); }
    public function isHQ(): bool { return $this->role === 'hq'; }
}
