<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardComment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'parent_id',
        'user_id',
        'author_name',
        'password',
        'content',
        'depth',
        'is_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_secret' => 'boolean',
        'depth' => 'integer',
    ];

    /**
     * 이 댓글의 작성자 관계
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 이 댓글의 부모 댓글 관계
     */
    public function parent()
    {
        return $this->belongsTo(BoardComment::class, 'parent_id');
    }

    /**
     * 이 댓글의 자식 댓글들 관계
     */
    public function replies()
    {
        return $this->hasMany(BoardComment::class, 'parent_id');
    }

    /**
     * 대댓글인지 확인
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * 댓글 깊이에 따른 들여쓰기 스타일 클래스 반환
     */
    public function getIndentClass(): string
    {
        return 'comment-depth-' . min($this->depth, 5); // 최대 5단계까지만
    }
}
