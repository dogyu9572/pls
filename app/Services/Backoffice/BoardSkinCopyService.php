<?php

namespace App\Services\Backoffice;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BoardSkinCopyService
{
    /**
     * 스킨 파일들을 게시판별 디렉토리로 복사합니다.
     */
    public function copySkinToBoard($skinDirectory, $boardSlug)
    {
        $sourcePath = resource_path("views/boards/skins/{$skinDirectory}");
        $targetPath = resource_path("views/boards/instances/{$boardSlug}");
        
        // 소스 스킨이 존재하는지 확인
        if (!File::exists($sourcePath)) {
            throw new \Exception("스킨 디렉토리를 찾을 수 없습니다: {$skinDirectory}");
        }
        
        // 타겟 디렉토리가 이미 존재하면 삭제
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }
        
        // 스킨 파일들을 복사
        File::copyDirectory($sourcePath, $targetPath);
        
        return $targetPath;
    }
    
    /**
     * 게시판별 스킨 파일 경로를 가져옵니다.
     */
    public function getBoardSkinPath($boardSlug)
    {
        return resource_path("views/boards/instances/{$boardSlug}");
    }
    
    /**
     * 게시판별 스킨 뷰 경로를 가져옵니다.
     */
    public function getBoardSkinViewPath($boardSlug, $viewType)
    {
        return "boards.instances.{$boardSlug}.{$viewType}";
    }
    
    /**
     * 게시판 삭제 시 스킨 파일들도 함께 삭제합니다.
     */
    public function deleteBoardSkin($boardSlug)
    {
        $targetPath = $this->getBoardSkinPath($boardSlug);
        
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }
    }
    
    /**
     * 게시판 스킨 파일이 존재하는지 확인합니다.
     */
    public function boardSkinExists($boardSlug)
    {
        $targetPath = $this->getBoardSkinPath($boardSlug);
        return File::exists($targetPath);
    }
}
