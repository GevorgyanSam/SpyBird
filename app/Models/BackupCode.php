<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupCode extends Model
{
    use HasFactory;

    protected $table = 'backup_codes';
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
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = false;
}