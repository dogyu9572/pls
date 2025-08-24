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
        'is_active'
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

        // 3. 경로의 마지막 부분으로 메뉴 찾기 (예: menus, boards 등)
        $pathParts = explode('/', trim($currentPath, '/'));
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
}
