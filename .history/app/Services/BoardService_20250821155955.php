<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardSkin;
use App\Models\BoardSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BoardService
{
    /**
     * 게시판 목록을 가져옵니다.
     */
    public function getBoards(int $perPage = 10)
    {
        return Board::with('skin')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * 활성화된 스킨 목록을 가져옵니다.
     */
    public function getActiveSkins()
    {
        return BoardSkin::where('is_active', true)->get();
    }

    /**
     * 게시판을 생성합니다.
     */
    public function createBoard(array $data): Board
    {
        // slug 자동 생성 (비어있거나 중복일 경우)
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            $data['slug'] = $this->resolveSlugConflict($data['slug']);
        }

        // 게시판 생성
        $board = Board::create($data);

        // 기본 설정값 저장
        if (isset($data['settings'])) {
            $this->saveBoardSettings($board, $data['settings']);
        }

        return $board;
    }

    /**
     * 게시판을 업데이트합니다.
     */
    public function updateBoard(Board $board, array $data): bool
    {
        $result = $board->update($data);

        // 설정값 업데이트
        if (isset($data['settings'])) {
            $this->saveBoardSettings($board, $data['settings']);
        }

        return $result;
    }

    /**
     * 게시판을 삭제합니다.
     */
    public function deleteBoard(Board $board): bool
    {
        try {
            // 1. 관련 설정 삭제
            BoardSetting::where('board_id', $board->id)->delete();
            
            // 2. 게시판 삭제
            $result = $board->delete();
            
            if ($result) {
                // 3. 관련 리소스 삭제 (BoardFileGeneratorService 사용)
                $fileGeneratorService = new \App\Services\BoardFileGeneratorService();
                $fileGeneratorService->deleteBoardResources($board);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('게시판 삭제 실패', [
                'board_id' => $board->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * 고유한 slug를 생성합니다.
     */
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (!Board::isSlugAvailable($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * slug 충돌을 해결합니다.
     */
    private function resolveSlugConflict(string $slug): string
    {
        if (Board::isSlugAvailable($slug)) {
            return $slug;
        }

        $originalSlug = $slug;
        $counter = 1;

        while (!Board::isSlugAvailable($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * 게시판 설정을 저장합니다.
     */
    private function saveBoardSettings(Board $board, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $board->saveSetting($key, $value);
        }
    }
}
