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
     * 특정 게시판을 가져옵니다.
     */
    public function getBoard(string $slug): Board
    {
        $board = Board::with('skin')->where('slug', $slug)->first();
        
        if (!$board) {
            abort(404, '게시판을 찾을 수 없습니다.');
        }
        
        return $board;
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

        // 커스텀 필드 설정 처리
        $customFieldsConfig = null;
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            $customFieldsConfig = $this->processCustomFieldsConfig($data['custom_fields']);
        }

        // 커스텀 필드 설정을 데이터에 추가
        if ($customFieldsConfig) {
            $data['custom_fields_config'] = $customFieldsConfig;
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
     * 커스텀 필드 설정을 처리합니다.
     */
    private function processCustomFieldsConfig(array $customFields): array
    {
        $processedFields = [];
        
        foreach ($customFields as $field) {
            if (empty($field['name']) || empty($field['label']) || empty($field['type'])) {
                continue; // 필수 값이 없으면 건너뛰기
            }
            
            $processedFields[] = [
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'max_length' => $field['max_length'] ?? null,
                'required' => (bool) ($field['required'] ?? false),
                'options' => $field['options'] ?? null,
                'placeholder' => $field['placeholder'] ?? null,
            ];
        }
        
        return $processedFields;
    }

    /**
     * 게시판을 업데이트합니다.
     */
    public function updateBoard(Board $board, array $data): bool
    {
        // 커스텀 필드 설정 처리
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            $customFieldsConfig = $this->processCustomFieldsConfig($data['custom_fields']);
            $data['custom_fields_config'] = $customFieldsConfig;
        }

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

    /**
     * 게시판 접근 권한을 확인합니다.
     */
    public function canAccessBoard(Board $board, string $permission): bool
    {
        if ($permission === 'public') {
            return true;
        }
        
        if ($permission === 'member' && auth()->check()) {
            return true;
        }
        
        if ($permission === 'admin' && auth()->check() && auth()->user()->is_admin) {
            return true;
        }
        
        return false;
    }

    /**
     * 게시글 목록을 가져옵니다.
     */
    public function getPosts(Board $board, array $searchParams = [])
    {
        $query = $board->posts()->with(['user', 'attachments']);
        
        // 검색 처리
        if (!empty($searchParams['search_query'])) {
            $searchField = $searchParams['search_field'] ?? 'title';
            $searchQuery = $searchParams['search_query'];
            
            if ($searchField === 'title') {
                $query->where('title', 'like', "%{$searchQuery}%");
            } elseif ($searchField === 'content') {
                $query->where('content', 'like', "%{$searchQuery}%");
            } elseif ($searchField === 'user') {
                $query->whereHas('user', function($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%");
                });
            }
        }
        
        $posts = $query->where('is_notice', false)
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);
        
        $notices = $board->posts()->with(['user', 'attachments'])
                           ->where('is_notice', true)
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return [
            'posts' => $posts,
            'notices' => $notices
        ];
    }

    /**
     * 특정 게시글을 가져옵니다.
     */
    public function getPost(Board $board, int $id)
    {
        $post = $board->posts()->with(['user', 'attachments'])->find($id);
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }
        
        return $post;
    }

    /**
     * 새 게시글을 생성합니다.
     */
    public function createPost(Board $board, array $data)
    {
        $data['user_id'] = auth()->id();
        $data['board_id'] = $board->id;
        
        return $board->posts()->create($data);
    }
}
