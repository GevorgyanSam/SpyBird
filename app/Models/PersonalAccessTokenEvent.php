<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessTokenEvent extends Model
{
    use HasFactory;

    protected $table = 'personal_access_token_events';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $fillable = [
        'token_id',
        'type',
        'ip',
        'user_agent'
    ];
    public $incrementing = true;
    public $timestamps = false;
}