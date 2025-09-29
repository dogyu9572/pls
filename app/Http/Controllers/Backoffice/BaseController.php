<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    /**
     * 뷰에 공통 데이터를 추가하는 메서드
     */
    protected function addCommonData($view, $data = [])
    {
        // 현재 경로에서 메뉴 정보 가져오기
        $currentPath = request()->path();
        $currentMenu = AdminMenu::getCurrentMenu($currentPath);       
        
        // 공통 데이터 추가
        $commonData = [
            'currentMenu' => $currentMenu,
            'pageTitle' => $currentMenu ? $currentMenu->name : '관리자',
        ];
        
        // 기존 데이터와 병합
        $viewData = array_merge($commonData, $data);
        
        return $view->with($viewData);
    }
    
    /**
     * 뷰 반환 시 공통 데이터 자동 추가
     */
    protected function view($view, $data = [])
    {
        return $this->addCommonData(view($view), $data);
    }
}
