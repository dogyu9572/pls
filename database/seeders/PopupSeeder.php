<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Popup;

class PopupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        Popup::query()->delete();

        // 테스트 팝업 데이터 생성
        $popups = [
            [
                'title' => '신규 서비스 안내',
                'use_period' => true,
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(30),
                'width' => 500,
                'height' => 400,
                'position_top' => 100,
                'position_left' => 200,
                'url' => 'https://example.com/new-service',
                'url_target' => '_blank',
                'popup_type' => 'image',
                'is_active' => true,
                'sort_order' => 100,
            ],
            [
                'title' => '이벤트 공지',
                'use_period' => false,
                'width' => 600,
                'height' => 500,
                'position_top' => 150,
                'position_left' => 150,
                'url' => 'https://example.com/event',
                'url_target' => '_self',
                'popup_type' => 'html',
                'popup_content' => '<div style="text-align: center; padding: 20px;"><h3>특별 이벤트</h3><p>지금 참여하세요!</p><button onclick="window.close()">닫기</button></div>',
                'is_active' => true,
                'sort_order' => 90,
            ],
            [
                'title' => '시스템 점검 안내',
                'use_period' => true,
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(3),
                'width' => 400,
                'height' => 300,
                'position_top' => 200,
                'position_left' => 300,
                'url' => 'https://example.com/maintenance',
                'url_target' => '_blank',
                'popup_type' => 'image',
                'is_active' => true,
                'sort_order' => 80,
            ],
        ];

        foreach ($popups as $popup) {
            Popup::create($popup);
        }
    }
}