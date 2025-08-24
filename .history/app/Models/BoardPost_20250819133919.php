<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardPost extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'board_id',
        'user_id',
        'author_name',
        'password',
        'title',
        'content',
        'view_count',
        'is_notice',
        'is_secret',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'view_count' => 'integer',
        'is_notice' => 'boolean',
        'is_secret' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * 이 게시글이 속한 게시판 관계
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * 이 게시글의 작성자 관계
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 이 게시글의 댓글들 관계
     */
    public function comments()
    {
        return $this->hasMany(BoardComment::class, 'post_id');
    }

    /**
     * 루트 댓글만 가져오기 (대댓글 제외)
     */
    public function rootComments()
    {
        return $this->comments()->whereNull('parent_id')->orderBy('created_at');
    }

    /**
     * 조회수 증가 메서드
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * 비밀글 접근 권한 체크
     */
    public function canAccess($user = null)
    {
        // 비밀글이 아니면 접근 가능
        if (!$this->is_secret) {
            return true;
        }

        // 사용자가 없는 경우 접근 불가
        if (!$user) {
            return false;
        }

        // 관리자이거나 작성자인 경우 접근 가능
        return $user->isAdmin() || $this->user_id === $user->id;
    }
}
