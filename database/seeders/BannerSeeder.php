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
                'banner_type' => 'image',
                'desktop_image' => 'banners/sample1.jpg',
                'sort_order' => 100,
            ],
            [
                'title' => '영상 배너 테스트',
                'main_text' => '동영상으로 만나는 새로운 경험',
                'sub_text' => '고품질 영상 콘텐츠',
                'url' => 'https://example.com/video',
                'url_target' => '_blank',
                'use_period' => false,
                'is_active' => true,
                'banner_type' => 'video',
                'video_file' => 'banners/videos/sample_video.mp4',
                'video_duration' => 8,
                'video_poster' => 'banners/video_poster.jpg',
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
                'banner_type' => 'image',
                'desktop_image' => 'banners/sample2.jpg',
                'sort_order' => 80,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
