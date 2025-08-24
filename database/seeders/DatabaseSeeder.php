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
        // 테스트 관리자 계정 생성
        User::create([
            'name' => '관리자',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // 관리자 메뉴 시더 실행
        $this->call(AdminMenuSeeder::class);

        // 게시판 스킨 시더 실행
        $this->call(BoardSkinSeeder::class);
    }
}
