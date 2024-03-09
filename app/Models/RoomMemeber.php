<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMemeber extends Model
{
    use HasFactory;

    protected $table = 'room_members';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'room_id',
        'created_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}