<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardComment extends Model
{
    use HasFactory, SoftDeletes;

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
        'depth' => 'integer',
        'is_secret' => 'boolean',
    ];

    /**
     * 이 댓글이 속한 게시글 관계
     */
    public function post()
    {
        return $this->belongsTo(BoardPost::class, 'post_id');
    }

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
     * 이 댓글의 자식 댓글들(답글) 관계
     */
    public function replies()
    {
        return $this->hasMany(BoardComment::class, 'parent_id');
    }

    /**
     * 비밀 댓글 접근 권한 체크
     */
    public function canAccess($user = null)
    {
        // 비밀 댓글이 아니면 접근 가능
        if (!$this->is_secret) {
            return true;
        }

        // 사용자가 없는 경우 접근 불가
        if (!$user) {
            return false;
        }

        // 관리자이거나 작성자인 경우 접근 가능
        return $user->isAdmin() || $this->user_id === $user->id || $this->post->user_id === $user->id;
    }
}
