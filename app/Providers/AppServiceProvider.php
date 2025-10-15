<?php

namespace App\Providers;

use App\Models\AdminMenu;
use App\Models\BoardPost;
use App\Models\Popup;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 백오피스 경로에서 현재 메뉴 정보를 뷰에 공유
        if (Request::is('backoffice*')) {
            View::composer('*', function ($view) {
                $currentPath = Request::path();
                $currentMenu = AdminMenu::getCurrentMenu($currentPath);

                // 현재 메뉴가 있으면 타이틀 생성, 없으면 기본 타이틀 사용
                $menuTitle = $currentMenu ? $currentMenu->name : '백오피스';
                $title = "백오피스 - {$menuTitle}";

                $view->with('menuTitle', $menuTitle);
                $view->with('title', $title);
                
                // 사이드바 데이터 추가 (모든 페이지에서 공통 사용)
                $view->with('siteTitle', \App\Models\Setting::getValue('site_title', '관리자'));
                
                // 사용자 권한에 따른 메뉴 필터링
                $user = Auth::user();
                if ($user && $user->isSuperAdmin()) {
                    // 슈퍼 관리자는 모든 메뉴 표시
                    $mainMenus = \App\Models\AdminMenu::getMainMenus();
                } elseif ($user) {
                    // 일반 관리자는 권한 있는 메뉴만 표시
                    $accessibleMenuIds = $user->accessibleMenus()->pluck('admin_menus.id')->toArray();
                    
                    // 부모 메뉴 가져오기 (자식 메뉴는 eager loading하지 않음)
                    $mainMenus = \App\Models\AdminMenu::whereNull('parent_id')
                        ->where('is_active', true)
                        ->orderBy('order')
                        ->get()
                        ->filter(function ($menu) use ($accessibleMenuIds) {
                            // 부모 메뉴 자체에 권한이 있는지 확인
                            $hasParentPermission = in_array($menu->id, $accessibleMenuIds);
                            
                            // 권한이 있는 자식 메뉴만 필터링하여 로드
                            $filteredChildren = \App\Models\AdminMenu::where('parent_id', $menu->id)
                                ->where('is_active', true)
                                ->orderBy('order')
                                ->get()
                                ->filter(function ($child) use ($accessibleMenuIds) {
                                    return in_array($child->id, $accessibleMenuIds);
                                });
                            
                            // 자식 메뉴를 필터링된 것으로 교체
                            $menu->setRelation('children', $filteredChildren);
                            
                            // 부모 메뉴 권한이 있거나, 권한 있는 자식 메뉴가 하나라도 있으면 표시
                            return $hasParentPermission || $filteredChildren->count() > 0;
                        });
                } else {
                    $mainMenus = collect();
                }
                
                $view->with('mainMenus', $mainMenus);
            });
        }

        // 프론트엔드 레이아웃에 Family Sites 및 팝업 데이터 공유
        View::composer('layouts.app', function ($view) {
            $model = (new BoardPost)->setTableBySlug('family_sites');
            $familySites = $model->newQuery()              
                ->orderBy('created_at', 'desc')
                ->get();

            // 활성화되고 게시기간 내의 팝업 조회
            $popups = Popup::active()
                ->inPeriod()
                ->ordered()
                ->get();

            $view->with('familySites', $familySites);
            $view->with('popups', $popups);
        });

        // 쿼리 로깅 활성화 (디버깅용)
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::info(
                    'SQL 쿼리 실행',
                    [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]
                );
            });
        }
    }
}
