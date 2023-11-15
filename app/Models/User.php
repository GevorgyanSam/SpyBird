<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean',
        'two_factor_authentication' => 'boolean',
        'activity' => 'boolean',
        'invisible' => 'boolean'
    ];
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'status',
        'two_factor_authentication',
        'activity',
        'invisible',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
    public function getNameAttribute($value)
    {
        return Str::title($value);
    }
    public function setNameAttribute($value)
    {
        return $this->attributes['name'] = strtolower($value);
    }
    public function setEmailAttribute($value)
    {
        return $this->attributes['email'] = strtolower($value);
    }
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }
    public function latestLoginInfo()
    {
        return $this->hasOne(LoginInfo::class)->latestOfMany();
    }
}