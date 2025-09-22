<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 사용자 시더 실행
        $this->call(UserSeeder::class);

        // 관리자 메뉴 시더 실행
        $this->call(AdminMenuSeeder::class);

        // 게시판 스킨 시더 실행
        $this->call(BoardSkinSeeder::class);

        // 게시판 시더 실행
        $this->call(BoardSeeder::class);

        // 배너 시더 실행
        $this->call(BannerSeeder::class);
    }
}
