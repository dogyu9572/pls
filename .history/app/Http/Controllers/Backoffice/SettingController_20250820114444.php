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

            $data = $request->except(['logo', 'favicon', '_token', 'remove_logo', 'remove_favicon']);
            \Log::info('요청 데이터 확인', [
                'all_data' => $request->all(),
                'remove_logo' => $request->has('remove_logo'),
                'remove_favicon' => $request->has('remove_favicon'),
                'data' => $data
            ]);

            // 기존 설정 가져오기 (파일 삭제용)
            $currentSetting = Setting::first();

            // 로고 파일 삭제 처리
            \Log::info('로고 삭제 처리 시작', [
                'has_remove_logo' => $request->has('remove_logo'),
                'current_setting' => $currentSetting ? $currentSetting->logo_path : 'null'
            ]);
            
            if ($request->has('remove_logo') && $currentSetting && $currentSetting->logo_path) {
                try {
                    // /storage/settings/filename.jpg → settings/filename.jpg 형태로 변환
                    $oldLogoPath = str_replace('/storage/', '', $currentSetting->logo_path);
                    \Log::info('로고 파일 삭제 시도', [
                        'original_path' => $currentSetting->logo_path,
                        'converted_path' => $oldLogoPath,
                        'exists' => Storage::disk('public')->exists($oldLogoPath)
                    ]);
                    
                    if (Storage::disk('public')->exists($oldLogoPath)) {
                        Storage::disk('public')->delete($oldLogoPath);
                        \Log::info('기존 로고 파일 삭제 성공', ['path' => $oldLogoPath]);
                    } else {
                        \Log::warning('삭제할 로고 파일이 존재하지 않음', ['path' => $oldLogoPath]);
                    }
                    $data['logo_path'] = null;
                    \Log::info('로고 경로를 null로 설정', ['data_logo_path' => $data['logo_path']]);
                } catch (\Exception $e) {
                    \Log::error('로고 파일 삭제 실패', ['error' => $e->getMessage()]);
                }
            } else {
                \Log::info('로고 삭제 조건 불충족', [
                    'has_remove_logo' => $request->has('remove_logo'),
                    'current_setting_exists' => $currentSetting ? 'yes' : 'no',
                    'logo_path_exists' => $currentSetting && $currentSetting->logo_path ? 'yes' : 'no'
                ]);
            }
            
            // 로고 파일 업로드 처리
            if ($request->hasFile('logo')) {
                try {
                    // 기존 로고 파일 삭제
                    if ($currentSetting && $currentSetting->logo_path) {
                        $oldLogoPath = str_replace('/storage/', '', $currentSetting->logo_path);
                        if (Storage::disk('public')->exists($oldLogoPath)) {
                            Storage::disk('public')->delete($oldLogoPath);
                        }
                    }
                    
                    $logoPath = $request->file('logo')->store('settings', 'public');
                    $data['logo_path'] = Storage::url($logoPath);
                    \Log::info('로고 파일 업로드 성공', ['path' => $data['logo_path']]);
                } catch (\Exception $e) {
                    \Log::error('로고 파일 업로드 실패', ['error' => $e->getMessage()]);
                }
            }

            // 파비콘 파일 삭제 처리
            if ($request->has('remove_favicon') && $currentSetting && $currentSetting->favicon_path) {
                try {
                    // /storage/settings/filename.png → settings/filename.png 형태로 변환
                    $oldFaviconPath = str_replace('/storage/', '', $currentSetting->favicon_path);
                    if (Storage::disk('public')->exists($oldFaviconPath)) {
                        Storage::disk('public')->delete($oldFaviconPath);
                        \Log::info('기존 파비콘 파일 삭제 성공', ['path' => $oldFaviconPath]);
                    }
                    $data['favicon_path'] = null;
                } catch (\Exception $e) {
                    \Log::error('파비콘 파일 삭제 실패', ['error' => $e->getMessage()]);
                }
            }
            
            // 파비콘 파일 업로드 처리
            if ($request->hasFile('favicon')) {
                try {
                    // 기존 파비콘 파일 삭제
                    if ($currentSetting && $currentSetting->favicon_path) {
                        $oldFaviconPath = str_replace('/storage/', '', $currentSetting->favicon_path);
                        if (Storage::disk('public')->exists($oldFaviconPath)) {
                            Storage::disk('public')->delete($oldFaviconPath);
                        }
                    }
                    
                    $faviconPath = $request->file('favicon')->store('settings', 'public');
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
