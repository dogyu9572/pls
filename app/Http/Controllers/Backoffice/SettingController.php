<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Services\Backoffice\SettingService;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * 기본설정 페이지를 표시합니다.
     */
    public function index()
    {
        $setting = $this->settingService->getSetting();
        return $this->view('backoffice.setting.index', compact('setting'));
    }

    /**
     * 기본설정을 저장합니다.
     */
    public function update(SettingRequest $request)
    {
        $success = $this->settingService->updateSetting($request);

        if ($success) {
            return redirect()->route('backoffice.setting.index')
                ->with('success', '기본설정이 저장되었습니다.');
        }

        return redirect()->route('backoffice.setting.index')
            ->with('error', '설정을 저장하는 중 오류가 발생했습니다.')
            ->withInput();
    }
}
