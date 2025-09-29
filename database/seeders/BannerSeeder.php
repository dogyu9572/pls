<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        Banner::query()->delete();

        // 테스트 배너 데이터 생성
        $banners = [
            [
                'title' => '신규 서비스 런칭',
                'main_text' => '새로운 기능을 만나보세요',
                'sub_text' => '더욱 편리해진 서비스',
                'url' => 'https://example.com/new-service',
                'url_target' => '_blank',
                'use_period' => true,
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'sort_order' => 100,
            ],
            [
                'title' => '특별 이벤트',
                'main_text' => '지금 참여하세요',
                'sub_text' => '한정 기간 특가',
                'url' => 'https://example.com/event',
                'url_target' => '_self',
                'use_period' => false,
                'is_active' => true,
                'sort_order' => 90,
            ],
            [
                'title' => '공지사항',
                'main_text' => '중요한 안내사항',
                'sub_text' => '확인해주세요',
                'url' => 'https://example.com/notice',
                'url_target' => '_self',
                'use_period' => true,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(7),
                'is_active' => true,
                'sort_order' => 80,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
