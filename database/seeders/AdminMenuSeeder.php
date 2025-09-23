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
                'parent_id' => null,
                'name' => '대시보드',
                'url' => '/backoffice',
                'icon' => 'fa-tachometer-alt',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-09-23 07:22:04',
                'permission_key' => null,
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'name' => '시스템 관리',
                'url' => null,
                'icon' => 'fa-cogs',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-09-23 07:22:04',
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
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-09-23 13:14:34',
                'permission_key' => null,
            ],
            [
                'id' => 6,
                'parent_id' => null,
                'name' => '기본설정',
                'url' => null,
                'icon' => 'fa-file-alt',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-09-23 07:22:12',
                'permission_key' => null,
            ],
            [
                'id' => 7,
                'parent_id' => 2,
                'name' => '게시판 관리',
                'url' => '/backoffice/boards',
                'icon' => 'fa-clipboard',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-09-23 13:17:39',
                'permission_key' => null,
            ],
            [
                'id' => 8,
                'parent_id' => 21,
                'name' => '팝업 관리',
                'url' => '/backoffice/popups',
                'icon' => 'fa-window-restore',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2025-05-05 09:33:08',
                'updated_at' => '2025-09-23 07:20:32',
                'permission_key' => null,
            ],
            [
                'id' => 9,
                'parent_id' => 2,
                'name' => '게시판 스킨 관리',
                'url' => '/backoffice/board-skins',
                'icon' => 'fa-id-card',
                'order' => 3,
                'is_active' => 0,
                'created_at' => '2025-05-06 09:58:14',
                'updated_at' => '2025-09-23 13:03:10',
                'permission_key' => null,
            ],
            [
                'id' => 10,
                'parent_id' => 19,
                'name' => '공지사항 게시판',
                'url' => '/backoffice/board-posts/notices',
                'icon' => 'fa-folder',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-05-06 12:44:51',
                'updated_at' => '2025-09-23 07:18:20',
                'permission_key' => null,
            ],
            [
                'id' => 17,
                'parent_id' => 19,
                'name' => '갤러리',
                'url' => '/backoffice/board-posts/gallerys',
                'icon' => 'fa-folder',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-08-21 07:20:49',
                'updated_at' => '2025-09-23 07:18:20',
                'permission_key' => null,
            ],
            [
                'id' => 18,
                'parent_id' => 21,
                'name' => '배너 관리',
                'url' => '/backoffice/banners',
                'icon' => 'fa-credit-card',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2025-08-24 09:35:23',
                'updated_at' => '2025-09-23 07:20:26',
                'permission_key' => null,
            ],
            [
                'id' => 19,
                'parent_id' => null,
                'name' => '게시판관리',
                'url' => null,
                'icon' => 'fa-chart-bar',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2025-08-24 09:36:13',
                'updated_at' => '2025-09-23 07:22:02',
                'permission_key' => null,
            ],
            [
                'id' => 20,
                'parent_id' => 6,
                'name' => '관리자 관리',
                'url' => '/backoffice/admins',
                'icon' => 'fa-paper-plane',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-09-19 00:19:49',
                'updated_at' => '2025-09-22 07:46:49',
                'permission_key' => null,
            ],
            [
                'id' => 21,
                'parent_id' => null,
                'name' => '홈페이지관리',
                'url' => null,
                'icon' => 'fa-home',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2025-09-23 07:20:08',
                'updated_at' => '2025-09-23 07:22:02',
                'permission_key' => null,
            ],
            [
                'id' => 22,
                'parent_id' => null,
                'name' => '기업정보관리',
                'url' => null,
                'icon' => 'fa-users',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2025-09-23 07:21:13',
                'updated_at' => '2025-09-23 07:22:12',
                'permission_key' => null,
            ],
            [
                'id' => 23,
                'parent_id' => null,
                'name' => '사업분야관리',
                'url' => null,
                'icon' => 'fa-file',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2025-09-23 07:21:26',
                'updated_at' => '2025-09-23 07:22:12',
                'permission_key' => null,
            ],
            [
                'id' => 24,
                'parent_id' => null,
                'name' => '채용관리',
                'url' => null,
                'icon' => 'fa-user',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2025-09-23 07:21:42',
                'updated_at' => '2025-09-23 07:22:12',
                'permission_key' => null,
            ],
            [
                'id' => 25,
                'parent_id' => 22,
                'name' => '인사말관리',
                'url' => '/backoffice/board-posts/greetings',
                'icon' => 'fa-folder',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2025-09-23 07:29:41',
                'updated_at' => '2025-09-23 13:14:56',
                'permission_key' => null,
            ],
            [
                'id' => 26,
                'parent_id' => 22,
                'name' => '국문연혁관리',
                'url' => '/backoffice/board-posts/company_history_ko',
                'icon' => 'fa-folder',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2025-09-23 08:14:55',
                'updated_at' => '2025-09-23 13:14:56',
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
