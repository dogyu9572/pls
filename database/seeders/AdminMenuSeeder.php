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

        // 대시보드
        AdminMenu::create([
            'id' => 1,
            'parent_id' => null,
            'name' => '대시보드',
            'url' => '/backoffice',
            'icon' => 'fa-tachometer-alt',
            'order' => 1,
            'is_active' => true,
        ]);

        // 시스템 관리
        AdminMenu::create([
            'id' => 2,
            'parent_id' => null,
            'name' => '시스템 관리',
            'url' => null,
            'icon' => 'fa-cogs',
            'order' => 1,
            'is_active' => true,
        ]);

        // 기본설정
        AdminMenu::create([
            'id' => 3,
            'parent_id' => 2,
            'name' => '관리자관리',
            'url' => '/backoffice/admins',
            'icon' => 'fa-users-cog',
            'order' => 1,
            'is_active' => true,
            'permission_key' => 'admin_management',
        ]);

        // 메뉴 관리
        AdminMenu::create([
            'id' => 4,
            'parent_id' => null,
            'name' => '메뉴 관리',
            'url' => '/backoffice/admin-menus',
            'icon' => 'fa-list',
            'order' => 2,
            'is_active' => true,
        ]);

        // 회원 관리
        AdminMenu::create([
            'id' => 5,
            'parent_id' => null,
            'name' => '회원 관리',
            'url' => '/backoffice/users',
            'icon' => 'fa-users',
            'order' => 2,
            'is_active' => true,
        ]);

        // 콘텐츠 관리
        AdminMenu::create([
            'id' => 6,
            'parent_id' => null,
            'name' => '콘텐츠 관리',
            'url' => null,
            'icon' => 'fa-file-alt',
            'order' => 2,
            'is_active' => true,
        ]);

        // 게시판 관리
        AdminMenu::create([
            'id' => 7,
            'parent_id' => 6,
            'name' => '게시판 관리',
            'url' => '/backoffice/boards',
            'icon' => 'fa-clipboard',
            'order' => 3,
            'is_active' => true,
        ]);

        // 팝업 관리
        AdminMenu::create([
            'id' => 8,
            'parent_id' => 6,
            'name' => '팝업 관리',
            'url' => '/backoffice/popups',
            'icon' => 'fa-window-restore',
            'order' => 3,
            'is_active' => true,
        ]);

        // 게시판 스킨 관리
        AdminMenu::create([
            'id' => 9,
            'parent_id' => 6,
            'name' => '게시판 스킨 관리',
            'url' => '/backoffice/board-skins',
            'icon' => 'fa-id-card',
            'order' => 4,
            'is_active' => false,
        ]);

        // 공지사항 게시판
        AdminMenu::create([
            'id' => 10,
            'parent_id' => 6,
            'name' => '공지사항 게시판',
            'url' => '/backoffice/board-posts/notices',
            'icon' => 'fa-folder',
            'order' => 5,
            'is_active' => true,
        ]);

        // 갤러리
        AdminMenu::create([
            'id' => 11,
            'parent_id' => 6,
            'name' => '갤러리',
            'url' => '/backoffice/board-posts/gallerys',
            'icon' => 'fa-folder',
            'order' => 6,
            'is_active' => true,
        ]);

        // 배너 관리
        AdminMenu::create([
            'id' => 12,
            'parent_id' => 6,
            'name' => '배너 관리',
            'url' => '/backoffice/banners',
            'icon' => 'fa-credit-card',
            'order' => 4,
            'is_active' => true,
        ]);

        // 통계
        AdminMenu::create([
            'id' => 13,
            'parent_id' => 6,
            'name' => '통계',
            'url' => null,
            'icon' => 'fa-chart-bar',
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
