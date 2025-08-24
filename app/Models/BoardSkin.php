<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardSkin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'directory',
        'description',
        'thumbnail',
        'options',
        'is_active',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * 이 스킨을 사용하는 게시판들 관계
     */
    public function boards()
    {
        return $this->hasMany(Board::class, 'skin_id');
    }

    /**
     * 스킨 디렉토리의 절대 경로 가져오기
     */
    public function getPathAttribute()
    {
        return resource_path('views/boards/skins/' . $this->directory);
    }

    /**
     * 기본 스킨 가져오기
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first() ?? self::first();
    }
}
