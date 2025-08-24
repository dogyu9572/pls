<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * 사용자 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        User::truncate();

        // 관리자
        User::create([
            'id' => 1,
            'name' => '관리자',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 슈퍼 관리자
        User::create([
            'id' => 2,
            'name' => '홈페이지관리자',
            'email' => 'admin@homepage.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }
}
