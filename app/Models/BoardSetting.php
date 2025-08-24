<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'board_id',
        'key',
        'value',
    ];

    /**
     * 이 설정이 속한 게시판 관계
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * 특정 게시판의 모든 설정을 배열로 가져오기
     */
    public static function getAllSettings($boardId)
    {
        $settings = self::where('board_id', $boardId)->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }

        return $result;
    }

    /**
     * 특정 게시판의 설정값 저장하기
     */
    public static function setSetting($boardId, $key, $value)
    {
        return self::updateOrCreate(
            [
                'board_id' => $boardId,
                'key' => $key
            ],
            [
                'value' => $value
            ]
        );
    }
}
