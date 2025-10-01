<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'referer',
        'session_id',
        'is_unique',
    ];

    protected $casts = [
        'is_unique' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
