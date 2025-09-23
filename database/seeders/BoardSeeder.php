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
        // 기존 데이터 삭제 (하드 삭제)
        Board::query()->forceDelete();

        // 실제 데이터베이스 데이터를 기반으로 생성
        $boards = [
            [
                'id' => 1,
                'name' => '공지사항',
                'slug' => 'notices',
                'description' => null,
                'skin_id' => 1,
                'is_active' => 1,
                'table_created' => 0,
                'list_count' => 10,
                'enable_notice' => 1,
                'is_single_page' => 0,
                'enable_sorting' => 0,
                'hot_threshold' => 100,
                'permission_read' => 'all',
                'permission_write' => 'all',
                'permission_comment' => 'all',
                'created_at' => '2025-09-23 06:55:06',
                'updated_at' => '2025-09-23 06:55:06',
                'deleted_at' => null,
                'custom_fields_config' => null,
            ],
            [
                'id' => 2,
                'name' => '갤러리',
                'slug' => 'gallerys',
                'description' => null,
                'skin_id' => 2,
                'is_active' => 1,
                'table_created' => 0,
                'list_count' => 10,
                'enable_notice' => 0,
                'is_single_page' => 0,
                'enable_sorting' => 0,
                'hot_threshold' => 100,
                'permission_read' => 'all',
                'permission_write' => 'all',
                'permission_comment' => 'all',
                'created_at' => '2025-09-23 06:55:06',
                'updated_at' => '2025-09-23 06:55:06',
                'deleted_at' => null,
                'custom_fields_config' => null,
            ],
            [
                'id' => 3,
                'name' => '인사말',
                'slug' => 'greetings',
                'description' => null,
                'skin_id' => 1,
                'is_active' => 1,
                'table_created' => 0,
                'list_count' => 10,
                'enable_notice' => 1,
                'is_single_page' => 1,
                'enable_sorting' => 0,
                'hot_threshold' => 100,
                'permission_read' => 'all',
                'permission_write' => 'all',
                'permission_comment' => 'all',
                'created_at' => '2025-09-23 07:34:15',
                'updated_at' => '2025-09-23 08:28:31',
                'deleted_at' => null,
                'custom_fields_config' => '[{"name": "greeting_ko", "type": "editor", "label": "인사말 국문", "options": null, "required": false, "max_length": null, "placeholder": null}, {"name": "greeting_en", "type": "editor", "label": "인사말 영문", "options": null, "required": false, "max_length": null, "placeholder": null}, {"name": "ceo_message_ko", "type": "text", "label": "대표이사 국문", "options": null, "required": false, "max_length": null, "placeholder": null}, {"name": "ceo_message_en", "type": "text", "label": "대표이사 영문", "options": null, "required": false, "max_length": null, "placeholder": null}]',
            ],
            [
                'id' => 4,
                'name' => '연혁관리',
                'slug' => 'company_history_ko',
                'description' => null,
                'skin_id' => 1,
                'is_active' => 1,
                'table_created' => 0,
                'list_count' => 10,
                'enable_notice' => 0,
                'is_single_page' => 0,
                'enable_sorting' => 1,
                'hot_threshold' => 100,
                'permission_read' => 'all',
                'permission_write' => 'all',
                'permission_comment' => 'all',
                'created_at' => '2025-09-23 13:19:47',
                'updated_at' => '2025-09-23 14:09:29',
                'deleted_at' => null,
                'custom_fields_config' => '[{"name": "year", "type": "select", "label": "년도", "options": "1990\\r\\n1991", "required": false, "max_length": null, "placeholder": null}]',
            ],
        ];

        foreach ($boards as $board) {
            Board::create($board);
        }
    }
}
