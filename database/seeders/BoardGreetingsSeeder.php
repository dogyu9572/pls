<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardGreetingsSeeder extends Seeder
{
    /**
     * 인사말 게시판 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        DB::table('board_greetings')->delete();

        // 실제 데이터베이스 데이터를 기반으로 생성
        $greetings = [
            [
                'id' => 1,
                'user_id' => null,
                'title' => '인사말',
                'content' => '인사말 내용',
                'author_name' => '관리자',
                'password' => null,
                'is_notice' => 1,
                'is_secret' => 0,
                'category' => '인사말',
                'attachments' => '[]',
                'view_count' => 0,
                'sort_order' => 0,
                'custom_fields' => '{"greeting_en": "<p><span style=\\"color: rgb(51, 51, 51); font-family: Pretendard, \\"Noto Sans KR\\", -apple-system, BlinkMacSystemFont, \\"Segoe UI\\", Roboto, Oxygen, Ubuntu, Cantarell, \\"Open Sans\\", \\"Helvetica Neue\\", sans-serif; font-weight: 600; letter-spacing: -0.28px;\\">인사말 영문2</span></p>", "greeting_ko": "<p><span style=\\"color: rgb(51, 51, 51); font-family: Pretendard, \\"Noto Sans KR\\", -apple-system, BlinkMacSystemFont, \\"Segoe UI\\", Roboto, Oxygen, Ubuntu, Cantarell, \\"Open Sans\\", \\"Helvetica Neue\\", sans-serif; font-weight: 600; letter-spacing: -0.28px;\\">인사말 국문23</span></p>", "ceo_message_en": "대표이사 영문", "ceo_message_ko": "대표이사 국문"}',
                'thumbnail' => null,
                'created_at' => '2025-09-23 07:54:30',
                'updated_at' => '2025-09-23 08:13:23',
                'deleted_at' => null,
            ],
        ];

        foreach ($greetings as $greeting) {
            DB::table('board_greetings')->insert($greeting);
        }
    }
}
