<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['username','password','role','city_id'];
    protected $hidden   = ['password'];

    public function city() { return $this->belongsTo(City::class); }

    public function isHQ(): bool { return $this->role === 'hq'; }
}
