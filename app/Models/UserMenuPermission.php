<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMenuPermission extends Model
{
    /**
     * 대량 할당 가능한 속성들
     */
    protected $fillable = [
        'user_id',
        'menu_id',
        'granted'
    ];

    /**
     * 타입 캐스팅
     */
    protected $casts = [
        'granted' => 'boolean',
    ];
}