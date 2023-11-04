<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedLoginAttempt extends Model
{
    use HasFactory;

    protected $table = 'failed_login_attempts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'type',
        'ip',
        'user_agent',
        'created_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}