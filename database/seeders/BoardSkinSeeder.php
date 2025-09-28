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

        // 실제 데이터베이스 데이터를 기반으로 생성
        $skins = [
            [
                'id' => 1,
                'name' => '기본 스킨',
                'directory' => 'default',
                'description' => '기본 게시판 스킨입니다. 모든 게시판에서 사용할 수 있는 기본 스킨입니다.',
                'thumbnail' => null,
                'options' => '{"show_view_count":true,"show_date":true,"list_date_format":"Y-m-d","view_date_format":"Y-m-d H:i"}',
                'is_active' => 1,
                'is_default' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-05-05 09:33:08',
            ],
            [
                'id' => 2,
                'name' => '갤러리 스킨',
                'directory' => 'gallery',
                'description' => '갤러리 형태의 게시판에서 사용할 수 있는 이미지 중심 스킨입니다.',
                'thumbnail' => null,
                'options' => '{"show_view_count":true,"show_date":true,"list_date_format":"Y-m-d","view_date_format":"Y-m-d H:i","gallery_columns":3,"thumbnail_width":300,"thumbnail_height":200}',
                'is_active' => 1,
                'is_default' => 0,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-05-05 09:33:08',
            ],
        ];

        foreach ($skins as $skin) {
            BoardSkin::create($skin);
        }
    }
}
