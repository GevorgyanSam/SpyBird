<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'spy' => 'boolean',
        'status' => 'boolean'
    ];
    protected $fillable = [
        'user_id',
        'spy',
        'status',
        'created_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}