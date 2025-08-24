<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class SettingController extends BaseController
{
    /**
     * 기본설정 페이지를 표시합니다.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // 테이블이 존재하는지 확인
            if (!Schema::hasTable('settings')) {
                // 테이블이 없으면 마이그레이션 실행
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--path' => 'database/migrations/2025_05_04_024858_create_settings_table.php']);
            }

            $setting = Setting::first() ?: new Setting();
            return $this->view('backoffice.setting.index', compact('setting'));
        } catch (QueryException $e) {
            // 데이터베이스 오류가 발생한 경우 빈 객체로 뷰 표시
            $setting = new Setting();
            return $this->view('backoffice.setting.index', compact('setting'))->with('error', '데이터베이스 연결 중 오류가 발생했습니다. 데이터베이스 설정을 확인해주세요.');
        }
    }

    /**
     * 기본설정을 저장합니다.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // 디버깅을 위해 요청 데이터 로깅
        \Log::info('설정 저장 시도', ['request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'site_title' => 'required|string|max:255',
            'site_url' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_tel' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'footer_text' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('유효성 검증 실패', ['errors' => $validator->errors()]);
            return redirect()->route('backoffice.setting.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // 테이블이 존재하는지 확인
            if (!Schema::hasTable('settings')) {
                \Log::info('settings 테이블이 존재하지 않아 마이그레이션 실행');
                // 테이블이 없으면 마이그레이션 실행
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--path' => 'database/migrations/2025_05_04_024858_create_settings_table.php']);
            }

            $data = $request->except(['logo', 'favicon', '_token']);
            \Log::info('저장할 데이터', ['data' => $data]);

            // 로고 파일 업로드 처리
            if ($request->hasFile('logo')) {
                try {
                    $logoPath = $request->file('logo')->store('public/settings');
                    $data['logo_path'] = Storage::url($logoPath);
                    \Log::info('로고 파일 업로드 성공', ['path' => $data['logo_path']]);
                } catch (\Exception $e) {
                    \Log::error('로고 파일 업로드 실패', ['error' => $e->getMessage()]);
                }
            }

            // 파비콘 파일 업로드 처리
            if ($request->hasFile('favicon')) {
                try {
                    $faviconPath = $request->file('favicon')->store('public/settings');
                    $data['favicon_path'] = Storage::url($faviconPath);
                    \Log::info('파비콘 파일 업로드 성공', ['path' => $data['favicon_path']]);
                } catch (\Exception $e) {
                    \Log::error('파비콘 파일 업로드 실패', ['error' => $e->getMessage()]);
                }
            }

            // 기존 설정을 업데이트하거나 새 설정을 생성
            \Log::info('Setting::updateOrCreateSettings 호출 전');
            $result = Setting::updateOrCreateSettings($data);
            \Log::info('Setting::updateOrCreateSettings 호출 결과', ['result' => $result]);

            return redirect()->route('backoffice.setting.index')
                ->with('success', '기본설정이 저장되었습니다.');
        } catch (\Exception $e) {
            // 오류 상세 정보 로깅
            \Log::error('설정 저장 중 예외 발생', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // 데이터베이스 오류 처리
            return redirect()->route('backoffice.setting.index')
                ->with('error', '설정을 저장하는 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }
}
