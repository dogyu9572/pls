<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoardSkin;

class BoardSkinSeeder extends Seeder
{
    /**
     * 게시판 스킨 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        BoardSkin::query()->delete();

        // 기본 스킨
        BoardSkin::create([
            'id' => 1,
            'name' => '기본 스킨',
            'directory' => 'default',
            'description' => '기본 게시판 스킨입니다. 모든 게시판에서 사용할 수 있는 기본 스킨입니다.',
            'is_default' => true,
            'is_active' => true,
        ]);

        // 갤러리 스킨
        BoardSkin::create([
            'id' => 2,
            'name' => '갤러리 스킨',
            'directory' => 'gallery',
            'description' => '갤러리 형태의 게시판에서 사용할 수 있는 이미지 중심 스킨입니다.',
            'is_default' => false,
            'is_active' => true,
        ]);
    }
}
