<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDataHistory extends Model
{
    use HasFactory;

    protected $table = 'user_data_history';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $fillable = [
        'user_id',
        'type',
        'from',
        'to',
        'created_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}