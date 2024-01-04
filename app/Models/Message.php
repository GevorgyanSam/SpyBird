<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean',
        'seen' => 'boolean',
        'liked' => 'boolean'
    ];
    protected $fillable = [
        'user_id',
        'room_id',
        'content',
        'type',
        'liked',
        'seen',
        'status',
        'created_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}