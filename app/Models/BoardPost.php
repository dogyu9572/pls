<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardPost extends Model
{
    use SoftDeletes;

    /**
     * 동적으로 테이블명 설정
     */
    public function setTableBySlug($slug)
    {
        $this->table = 'board_' . $slug;
        return $this;
    }

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'author_name',
        'password',
        'is_notice',
        'is_secret',
        'category',
        'attachments',
        'view_count',
        'sort_order',
        'custom_fields',
        'thumbnail',
    ];

    protected $casts = [
        'is_notice' => 'boolean',
        'is_secret' => 'boolean',
        'attachments' => 'array',
        'custom_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * 포맷된 날짜 (Y.m.d)
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y.m.d');
    }

    /**
     * 최근 7일 이내 게시글 여부
     */
    public function getIsNewAttribute()
    {
        return $this->created_at->diffInDays(now()) < 7;
    }

    /**
     * 작성자 관계
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
