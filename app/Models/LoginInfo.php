<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginInfo extends Model
{
    use HasFactory;

    protected $table = 'login_info';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $fillable = [
        'user_id',
        'ip',
        'user_agent',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}