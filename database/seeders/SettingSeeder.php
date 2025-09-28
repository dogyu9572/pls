<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * 기본 설정 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        Setting::truncate();

        // 기본 설정
        Setting::create([
            'id' => 1,
            'site_title' => '홈페이지코리아',
            'site_url' => 'https://homepagekorea.net/',
            'admin_email' => 'cdg9572@gmail.com',
            'company_name' => null,
            'company_address' => 'Hwagok-dong',
            'company_tel' => null,
            'logo_path' => '/storage/settings/9kGYFymMEoyqKb5K3FHuuUk3s3Nnmf6as6Uikj2u.jpg',
            'favicon_path' => null,
            'footer_text' => null,
        ]);
    }
}
