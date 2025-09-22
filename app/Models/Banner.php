<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'main_text',
        'sub_text',
        'url',
        'url_target',
        'start_date',
        'end_date',
        'use_period',
        'is_active',
        'desktop_image',
        'mobile_image',
        'video_url',
        'sort_order',
    ];

    protected $casts = [
        'use_period' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $attributes = [
        'url_target' => '_self',
    ];

    /**
     * 활성화된 배너만 조회하는 스코프
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 게시 기간 내 배너만 조회하는 스코프
     */
    public function scopeInPeriod($query)
    {
        return $query->where(function ($q) {
            $q->where('use_period', false)
              ->orWhere(function ($periodQuery) {
                  $now = now();
                  $periodQuery->where('use_period', true)
                             ->where('start_date', '<=', $now)
                             ->where('end_date', '>=', $now);
              });
        });
    }

    /**
     * 정렬된 배너 조회하는 스코프
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'desc')->orderBy('created_at', 'desc');
    }
}
