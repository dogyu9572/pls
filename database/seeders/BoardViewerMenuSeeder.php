<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminMenu;

class BoardViewerMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 게시판 메뉴 그룹이 이미 있는지 확인
        $boardGroup = AdminMenu::where('name', '게시판')->whereNull('parent_id')->first();

        // 게시판 그룹이 없으면 생성
        if (!$boardGroup) {
            $boardGroup = AdminMenu::create([
                'name' => '게시판',
                'url' => null,
                'icon' => 'fa-clipboard-list',
                'order' => 20,
                'is_active' => true
            ]);
        }

        // 기존에 '게시판 보기' 메뉴 항목이 있는지 확인
        $exists = AdminMenu::where('name', '게시판 보기')
                          ->where('parent_id', $boardGroup->id)
                          ->exists();

        // 없으면 생성
        if (!$exists) {
            AdminMenu::create([
                'parent_id' => $boardGroup->id,
                'name' => '게시판 보기',
                'url' => '/backoffice/board-viewer',
                'icon' => 'fa-eye',
                'order' => 15,
                'is_active' => true
            ]);

            $this->command->info('게시판 보기 메뉴가 추가되었습니다.');
        } else {
            $this->command->info('게시판 보기 메뉴가 이미 존재합니다.');
        }
    }
}
