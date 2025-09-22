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
        Board::query()->delete();

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
                'hot_threshold' => 100,
                'permission_read' => 'all',
                'permission_write' => 'all',
                'permission_comment' => 'all',
                'created_at' => '2025-08-24 09:37:48',
                'updated_at' => '2025-09-19 04:25:40',
                'deleted_at' => null,
                'custom_fields_config' => '[{"name": "select", "type": "select", "label": "구분", "options": "구분1\\r\\n구분2\\r\\n구분3", "required": true, "max_length": null, "placeholder": null}, {"name": "date", "type": "date", "label": "날짜", "options": null, "required": false, "max_length": null, "placeholder": null}, {"name": "text", "type": "text", "label": "추가정보", "options": null, "required": false, "max_length": null, "placeholder": "추가 정보를 입력하세요"}]',
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
                'hot_threshold' => 100,
                'permission_read' => 'all',
                'permission_write' => 'all',
                'permission_comment' => 'all',
                'created_at' => '2025-08-24 09:38:02',
                'updated_at' => '2025-08-24 09:38:02',
                'deleted_at' => null,
                'custom_fields_config' => '[{"name": "check", "type": "checkbox", "label": "구분", "options": "구분1\\r\\n구분2\\r\\n구분3", "required": true, "max_length": null, "placeholder": null}, {"name": "editor", "type": "editor", "label": "상세설명", "options": null, "required": false, "max_length": null, "placeholder": null}]',
            ],
        ];

        foreach ($boards as $board) {
            Board::create($board);
        }
    }
}
