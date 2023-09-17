<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $attributes = [
        'status' => 1,
        'two_factor_authentication' => 0
    ];
    protected $casts = [
        'status' => 'boolean',
        'two_factor_authentication' => 'boolean'
    ];
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'status',
        'two_factor_authentication',
        'created_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}