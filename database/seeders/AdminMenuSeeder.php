<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminMenu;

class AdminMenuSeeder extends Seeder
{
    /**
     * 관리자 메뉴 데이터를 시드합니다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        AdminMenu::query()->delete();

        // 실제 데이터베이스 데이터를 기반으로 생성
        $menus = [
            [
                'id' => 1,
                'parent_id' => 2,
                'name' => '대시보드',
                'url' => '/backoffice',
                'icon' => 'fa-tachometer-alt',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 09:34:05',
                'permission_key' => null,
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'name' => '시스템 관리',
                'url' => null,
                'icon' => 'fa-cogs',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 09:36:20',
                'permission_key' => null,
            ],
            [
                'id' => 3,
                'parent_id' => 6,
                'name' => '기본설정',
                'url' => '/backoffice/setting',
                'icon' => 'fa-sliders-h',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 09:34:00',
                'permission_key' => null,
            ],
            [
                'id' => 4,
                'parent_id' => 2,
                'name' => '메뉴 관리',
                'url' => '/backoffice/admin-menus',
                'icon' => 'fa-list',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 13:28:26',
                'permission_key' => null,
            ],
            [
                'id' => 5,
                'parent_id' => 6,
                'name' => '회원 관리',
                'url' => '/backoffice/users',
                'icon' => 'fa-users',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 09:34:49',
                'permission_key' => null,
            ],
            [
                'id' => 6,
                'parent_id' => null,
                'name' => '콘텐츠 관리',
                'url' => null,
                'icon' => 'fa-file-alt',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 09:36:20',
                'permission_key' => null,
            ],
            [
                'id' => 7,
                'parent_id' => 2,
                'name' => '게시판 관리',
                'url' => '/backoffice/boards',
                'icon' => 'fa-clipboard',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-05-06 13:35:09',
                'permission_key' => null,
            ],
            [
                'id' => 8,
                'parent_id' => 6,
                'name' => '팝업 관리',
                'url' => '/backoffice/popups',
                'icon' => 'fa-window-restore',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-08-24 09:34:51',
                'permission_key' => null,
            ],
            [
                'id' => 9,
                'parent_id' => 2,
                'name' => '게시판 스킨 관리',
                'url' => '/backoffice/board-skins',
                'icon' => 'fa-id-card',
                'order' => 4,
                'is_active' => 0,
                'created_at' => '2025-05-06 09:58:14',
                'updated_at' => '2025-08-24 13:28:33',
                'permission_key' => null,
            ],
            [
                'id' => 10,
                'parent_id' => 19,
                'name' => '공지사항 게시판',
                'url' => '/backoffice/board-posts/notices',
                'icon' => 'fa-folder',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2025-05-06 12:44:51',
                'updated_at' => '2025-08-24 09:38:10',
                'permission_key' => null,
            ],
            [
                'id' => 17,
                'parent_id' => 19,
                'name' => '갤러리',
                'url' => '/backoffice/board-posts/gallerys',
                'icon' => 'fa-folder',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2025-08-21 07:20:49',
                'updated_at' => '2025-08-24 09:39:07',
                'permission_key' => null,
            ],
            [
                'id' => 18,
                'parent_id' => 6,
                'name' => '배너 관리',
                'url' => '/backoffice/banners',
                'icon' => 'fa-credit-card',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2025-08-24 09:35:23',
                'updated_at' => '2025-08-24 09:35:28',
                'permission_key' => null,
            ],
            [
                'id' => 19,
                'parent_id' => null,
                'name' => '통계',
                'url' => null,
                'icon' => 'fa-chart-bar',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2025-08-24 09:36:13',
                'updated_at' => '2025-08-24 09:36:20',
                'permission_key' => null,
            ],
            [
                'id' => 20,
                'parent_id' => 6,
                'name' => '관리자 관리',
                'url' => '/backoffice/admins',
                'icon' => 'fa-paper-plane',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2025-09-19 00:19:49',
                'updated_at' => '2025-09-19 00:19:49',
                'permission_key' => null,
            ],
        ];

        // parent_id가 null인 메뉴들을 먼저 생성
        $parentMenus = array_filter($menus, function($menu) {
            return $menu['parent_id'] === null;
        });
        
        foreach ($parentMenus as $menu) {
            AdminMenu::create($menu);
        }
        
        // 그 다음에 자식 메뉴들을 생성
        $childMenus = array_filter($menus, function($menu) {
            return $menu['parent_id'] !== null;
        });
        
        foreach ($childMenus as $menu) {
            AdminMenu::create($menu);
        }
    }
}
