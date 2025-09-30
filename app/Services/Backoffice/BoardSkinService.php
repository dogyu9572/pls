<?php

namespace App\Services\Backoffice;

use App\Models\BoardSkin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BoardSkinService
{
    /**
     * 모든 게시판 스킨을 가져옵니다.
     */
    public function getAllSkins()
    {
        return BoardSkin::orderBy('name')->get();
    }

    /**
     * 새 게시판 스킨을 생성합니다.
     */
    public function createSkin(array $data)
    {
        // 스킨 파일이 업로드된 경우 처리
        if (isset($data['skin_file']) && $data['skin_file']->isValid()) {
            $data['file_path'] = $this->uploadSkinFile($data['skin_file']);
        }

        return BoardSkin::create($data);
    }

    /**
     * 게시판 스킨을 업데이트합니다.
     */
    public function updateSkin(BoardSkin $skin, array $data)
    {
        // 스킨 파일이 업로드된 경우 처리
        if (isset($data['skin_file']) && $data['skin_file']->isValid()) {
            // 기존 파일 삭제
            if ($skin->file_path) {
                $this->deleteSkinFile($skin->file_path);
            }
            $data['file_path'] = $this->uploadSkinFile($data['skin_file']);
        }

        $skin->update($data);
        return $skin;
    }

    /**
     * 게시판 스킨을 삭제합니다.
     */
    public function deleteSkin(BoardSkin $skin)
    {
        // 스킨 파일이 있는 경우 삭제
        if ($skin->file_path) {
            $this->deleteSkinFile($skin->file_path);
        }

        $skin->delete();
    }

    /**
     * 스킨 파일을 업로드합니다.
     */
    protected function uploadSkinFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('board_skins', $fileName, 'public');
        
        return $path;
    }

    /**
     * 스킨 파일을 삭제합니다.
     */
    protected function deleteSkinFile($filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    /**
     * 기본 스킨을 설정합니다.
     */
    public function setDefaultSkin(BoardSkin $skin)
    {
        // 기존 기본 스킨 해제
        BoardSkin::where('is_default', true)->update(['is_default' => false]);
        
        // 새 기본 스킨 설정
        $skin->update(['is_default' => true]);
        
        return $skin;
    }

    /**
     * 스킨 사용 가능 여부를 확인합니다.
     */
    public function isSkinAvailable(BoardSkin $skin)
    {
        return $skin->is_active && File::exists(public_path('storage/' . $skin->file_path));
    }
}
