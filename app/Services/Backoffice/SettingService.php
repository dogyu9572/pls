<?php

namespace App\Services\Backoffice;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class SettingService
{
    /**
     * 설정 데이터를 가져옵니다.
     */
    public function getSetting()
    {
        try {
            // 테이블이 존재하는지 확인
            if (!Schema::hasTable('settings')) {
                $this->runMigration();
            }

            return Setting::first() ?: new Setting();
        } catch (QueryException $e) {
            Log::error('설정 데이터 조회 실패', ['error' => $e->getMessage()]);
            return new Setting();
        }
    }

    /**
     * 설정을 저장합니다.
     */
    public function updateSetting(Request $request): bool
    {
        try {
            // 테이블이 존재하는지 확인
            if (!Schema::hasTable('settings')) {
                $this->runMigration();
            }

            $data = $request->except(['logo', 'favicon', '_token', 'remove_logo', 'remove_favicon']);
            $currentSetting = Setting::first();

            // 로고 파일 처리
            $data = $this->handleLogoFile($request, $currentSetting, $data);

            // 파비콘 파일 처리
            $data = $this->handleFaviconFile($request, $currentSetting, $data);

            // 설정 저장
            Setting::updateOrCreateSettings($data);

            return true;
        } catch (\Exception $e) {
            Log::error('설정 저장 실패', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * 로고 파일을 처리합니다.
     */
    private function handleLogoFile(Request $request, $currentSetting, array $data): array
    {
        // 로고 파일 삭제 처리
        if ($request->has('remove_logo') && $currentSetting && $currentSetting->logo_path) {
            $this->deleteFile($currentSetting->logo_path);
            $data['logo_path'] = null;
        }

        // 로고 파일 업로드 처리
        if ($request->hasFile('logo')) {
            // 기존 로고 파일 삭제
            if ($currentSetting && $currentSetting->logo_path) {
                $this->deleteFile($currentSetting->logo_path);
            }

            $logoPath = $request->file('logo')->store('settings', 'public');
            $data['logo_path'] = Storage::url($logoPath);
        }

        return $data;
    }

    /**
     * 파비콘 파일을 처리합니다.
     */
    private function handleFaviconFile(Request $request, $currentSetting, array $data): array
    {
        // 파비콘 파일 삭제 처리
        if ($request->has('remove_favicon') && $currentSetting && $currentSetting->favicon_path) {
            $this->deleteFile($currentSetting->favicon_path);
            $data['favicon_path'] = null;
        }

        // 파비콘 파일 업로드 처리
        if ($request->hasFile('favicon')) {
            // 기존 파비콘 파일 삭제
            if ($currentSetting && $currentSetting->favicon_path) {
                $this->deleteFile($currentSetting->favicon_path);
            }

            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $data['favicon_path'] = Storage::url($faviconPath);
        }

        return $data;
    }

    /**
     * 파일을 삭제합니다.
     */
    private function deleteFile(string $filePath): void
    {
        try {
            // /storage/settings/filename.jpg → settings/filename.jpg 형태로 변환
            $relativePath = str_replace('/storage/', '', $filePath);
            
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
                Log::info('파일 삭제 성공', ['path' => $relativePath]);
            }
        } catch (\Exception $e) {
            Log::error('파일 삭제 실패', ['path' => $filePath, 'error' => $e->getMessage()]);
        }
    }

    /**
     * 마이그레이션을 실행합니다.
     */
    private function runMigration(): void
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--path' => 'database/migrations/2025_05_04_024858_create_settings_table.php'
            ]);
            Log::info('settings 테이블 마이그레이션 실행 완료');
        } catch (\Exception $e) {
            Log::error('마이그레이션 실행 실패', ['error' => $e->getMessage()]);
        }
    }
}
