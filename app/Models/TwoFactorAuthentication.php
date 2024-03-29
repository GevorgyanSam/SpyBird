<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactorAuthentication extends Model
{
    use HasFactory;

    protected $table = 'two_factor_authentication';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $fillable = [
        'user_id',
        'code',
        'status',
        'created_at',
        'expires_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}