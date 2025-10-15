<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    use HasFactory;

    /**
     * 대량 할당 가능한 속성들
     */
    protected $fillable = [
        'parent_id',
        'name',
        'url',
        'icon',
        'order',
        'is_active',
        'permission_key'
    ];

    /**
     * 부모 메뉴와의 관계
     */
    public function parent()
    {
        return $this->belongsTo(AdminMenu::class, 'parent_id');
    }

    /**
     * 자식 메뉴들과의 관계
     */
    public function children()
    {
        return $this->hasMany(AdminMenu::class, 'parent_id')->orderBy('order');
    }

    /**
     * 이 메뉴와 연결된 게시판 관계
     */
    public function board()
    {
        return $this->belongsTo(Board::class, 'url', 'slug');
    }

    /**
     * 최상위 메뉴만 가져오기 (1차 메뉴)
     */
    public static function getMainMenus()
    {
        return self::whereNull('parent_id')
                   ->where('is_active', true)
                   ->orderBy('order')
                   ->get();
    }

    /**
     * 전체 메뉴 구조를 계층적으로 가져오기
     */
    public static function getMenuTree()
    {
        return self::with('children')
                   ->whereNull('parent_id')
                   ->orderBy('order')
                   ->get();
    }

    /**
     * 현재 URL에 해당하는 메뉴 가져오기
     *
     * @param string $currentPath 현재 경로
     * @return AdminMenu|null 메뉴 객체 또는 null
     */
    public static function getCurrentMenu($currentPath)
    {
        // 1. 정확히 일치하는 메뉴 찾기 (가장 우선)
        $exactMenu = self::where('url', $currentPath)
                         ->where('is_active', true)
                         ->first();
        
        if ($exactMenu) {
            return $exactMenu;
        }

        // 2. /backoffice/ 접두사가 없는 경우 추가해서 찾기
        if (!str_starts_with($currentPath, '/backoffice/')) {
            $exactMenuWithPrefix = self::where('url', '/backoffice/' . $currentPath)
                                       ->where('is_active', true)
                                       ->first();
            
            if ($exactMenuWithPrefix) {
                return $exactMenuWithPrefix;
            }
        }

        // 3. 경로의 두 번째 부분으로 메뉴 찾기 (예: boards, menus 등)
        $pathParts = explode('/', trim($currentPath, '/'));
        if (count($pathParts) >= 2) {
            $secondPath = $pathParts[1]; // backoffice/boards/create 에서 'boards' 부분
            $secondPathMenu = self::where('url', 'LIKE', '%' . $secondPath)
                                  ->where('is_active', true)
                                  ->orderBy('id')
                                  ->first();
            
            if ($secondPathMenu) {
                return $secondPathMenu;
            }
        }

        // 4. 경로의 마지막 부분으로 메뉴 찾기 (백업)
        if (!empty($pathParts)) {
            $lastPath = end($pathParts);
            $lastPathMenu = self::where('url', 'LIKE', '%' . $lastPath)
                                 ->where('is_active', true)
                                 ->orderBy('id')
                                 ->first();
            
            if ($lastPathMenu) {
                return $lastPathMenu;
            }
        }

        return null;
    }

    /**
     * 이 메뉴에 대한 사용자 권한들과의 관계
     */
    public function userPermissions()
    {
        return $this->hasMany(UserMenuPermission::class, 'menu_id');
    }

    /**
     * 이 메뉴에 접근 권한이 있는 사용자들
     */
    public function authorizedUsers()
    {
        return $this->belongsToMany(User::class, 'user_menu_permissions', 'menu_id', 'user_id')
            ->wherePivot('granted', true)
            ->withPivot('granted')
            ->withTimestamps();
    }

    /**
     * 권한이 필요한 메뉴들만 조회 (permission_key가 있는 메뉴)
     */
    public static function getPermissionRequiredMenus()
    {
        return self::whereNotNull('permission_key')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * 메뉴 트리 구조로 권한 메뉴들을 가져오기
     */
    public static function getPermissionMenuTree()
    {
        return self::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($menu) {
                // 자식 메뉴 중 활성화된 것만 필터링
                $menu->children = $menu->children->where('is_active', true);
                return $menu;
            });
    }
}
