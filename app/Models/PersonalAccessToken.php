<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;

    protected $table = 'personal_access_tokens';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $fillable = [
        'user_id',
        'type',
        'token',
        'status',
        'created_at',
        'expires_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}