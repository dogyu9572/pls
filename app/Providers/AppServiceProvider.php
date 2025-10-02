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
                $view->with('mainMenus', \App\Models\AdminMenu::getMainMenus());
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
