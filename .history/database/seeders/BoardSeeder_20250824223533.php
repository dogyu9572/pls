<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Board;

class BoardSeeder extends Seeder
{
    /**
     * 게시판 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        Board::truncate();

        // 공지사항 게시판
        Board::create([
            'id' => 1,
            'name' => '공지사항 게시판',
            'slug' => 'notices',
            'description' => null,
            'skin_id' => 1,
            'is_active' => true,
        ]);

        // 갤러리 게시판
        Board::create([
            'id' => 2,
            'name' => '갤러리',
            'slug' => 'gallerys',
            'description' => null,
            'skin_id' => 2,
            'is_active' => true,
        ]);
    }
}
