<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'skin_id',
        'is_active',
        'list_count',
        'enable_notice',
        'permission_read',
        'permission_write',
        'permission_comment',
        'custom_fields_config',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'list_count' => 'integer',
        'enable_notice' => 'boolean',
        'custom_fields_config' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
        'list_count' => 10,
        'enable_notice' => false,
    ];

    /**
     * 이 게시판에 속한 게시글들 관계
     */
    public function posts()
    {
        return $this->hasMany(BoardPost::class);
    }

    /**
     * 이 게시판에 속한 댓글들 관계
     */
    public function comments()
    {
        return $this->hasManyThrough(
            BoardComment::class,
            BoardPost::class,
            'board_id', // 로컬 키 (boards.id와 연결되는 board_posts 테이블의 외래 키)
            'post_id',  // 원격 키 (board_posts.id와 연결되는 board_comments 테이블의 외래 키)
            'id',       // 로컬 테이블의 기본 키 (boards.id)
            'id'        // 중간 테이블의 기본 키 (board_posts.id)
        );
    }

    /**
     * 이 게시판에 적용된 스킨 관계
     */
    public function skin()
    {
        return $this->belongsTo(BoardSkin::class, 'skin_id');
    }

    /**
     * 이 게시판의 설정값들 관계
     */
    public function settings()
    {
        return $this->hasMany(BoardSetting::class);
    }

    /**
     * 설정값 가져오기
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->settings()->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * 이 게시판의 게시글 수 가져오기
     */
    public function getPostsCount(): int
    {
        try {
            $tableName = 'board_' . $this->slug;
            return \Illuminate\Support\Facades\DB::table($tableName)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 이 게시판의 댓글 수 가져오기
     */
    public function getCommentsCount(): int
    {
        try {
            $tableName = 'board_' . $this->slug . '_comments';
            return \Illuminate\Support\Facades\DB::table($tableName)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
    }

    /**
     * 설정값 저장하기
     */
    public function saveSetting($key, $value)
    {
        return $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
    
    /**
     * 이 게시판의 커스텀 스킨 뷰 경로를 가져옵니다.
     */
    public function getCustomSkinViewPath($viewType)
    {
        return "boards.instances.{$this->slug}.{$viewType}";
    }
    
    /**
     * 이 게시판이 커스텀 스킨을 가지고 있는지 확인합니다.
     */
    public function hasCustomSkin()
    {
        $customSkinPath = resource_path("views/boards/instances/{$this->slug}");
        return \Illuminate\Support\Facades\File::exists($customSkinPath);
    }
    
    /**
     * 이 게시판의 스킨 뷰 경로를 가져옵니다 (커스텀 우선, 기본 스킨 대체).
     */
    public function getSkinViewPath($viewType)
    {
        // 커스텀 스킨이 있으면 커스텀 스킨 사용
        if ($this->hasCustomSkin()) {
            return $this->getCustomSkinViewPath($viewType);
        }
        
        // 없으면 기본 스킨 사용
        return "boards.skins.{$this->skin->directory}.{$viewType}";
    }
    
    /**
     * slug가 중복되지 않는지 확인합니다 (삭제된 데이터 제외).
     */
    public static function isSlugAvailable($slug, $excludeId = null)
    {
        $query = self::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }

    /**
     * 공지 기능이 활성화되어 있는지 확인합니다.
     */
    public function isNoticeEnabled(): bool
    {
        return (bool) $this->enable_notice;
    }

    /**
     * 공지글을 포함한 게시글 목록을 가져옵니다.
     */
    public function getPostsWithNotices($perPage = 15)
    {
        return $this->posts()
            ->orderBy('is_notice', 'desc')  // 공지글이 먼저
            ->orderBy('created_at', 'desc') // 그 다음 최신순
            ->paginate($perPage);
    }

    /**
     * 공지글만 가져옵니다.
     */
    public function getNotices()
    {
        return $this->posts()
            ->where('is_notice', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 일반 게시글만 가져옵니다 (공지글 제외).
     */
    public function getRegularPosts($perPage = 15)
    {
        return $this->posts()
            ->where('is_notice', false)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
