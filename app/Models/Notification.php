<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $fillable = [
        'user_id',
        'sender_id',
        'type',
        'content',
        'status',
        'created_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}