<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardSkin;
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
     * 필터링된 게시판 목록을 가져옵니다.
     */
    public function getBoardsWithFilters(Request $request)
    {
        $query = Board::with('skin');
        
        // 게시판명 검색
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // 활성화 상태 필터
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // 스킨 필터
        if ($request->filled('skin_id')) {
            $query->where('skin_id', $request->skin_id);
        }
        
        // 등록일 필터
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        
        // 목록 개수 설정
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
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

        // enable_notice 기본값 설정 (체크되지 않은 경우 false)
        if (!isset($data['enable_notice'])) {
            $data['enable_notice'] = false;
        }

        // 커스텀 필드 설정 처리
        $customFieldsConfig = null;
        if (isset($data['custom_fields']) && !empty($data['custom_fields'])) {
            // 문자열로 온 경우 JSON 디코딩
            if (is_string($data['custom_fields'])) {
                $data['custom_fields'] = json_decode($data['custom_fields'], true) ?: [];
            }
            
            // 배열이고 비어있지 않은 경우에만 처리
            if (is_array($data['custom_fields']) && !empty($data['custom_fields'])) {
                $customFieldsConfig = $this->processCustomFieldsConfig($data['custom_fields']);
            }
        }

        // 커스텀 필드 설정을 데이터에 추가 (null이면 빈 설정으로 처리)
        $data['custom_fields_config'] = $customFieldsConfig;

        // 게시판 생성
        $board = Board::create($data);


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
        // enable_notice 기본값 설정 (체크되지 않은 경우 false)
        if (!isset($data['enable_notice'])) {
            $data['enable_notice'] = false;
        }

        // is_single_page 기본값 설정 (체크되지 않은 경우 false)
        if (!isset($data['is_single_page'])) {
            $data['is_single_page'] = false;
        }

        // enable_sorting 기본값 설정 (체크되지 않은 경우 false)
        if (!isset($data['enable_sorting'])) {
            $data['enable_sorting'] = false;
        }

        // 커스텀 필드 설정 처리
        if (isset($data['custom_fields'])) {
            // custom_fields가 문자열로 오는 경우 배열로 변환
            if (is_string($data['custom_fields'])) {
                $data['custom_fields'] = json_decode($data['custom_fields'], true) ?: [];
            }
            
            if (is_array($data['custom_fields']) && !empty($data['custom_fields'])) {
                $customFieldsConfig = $this->processCustomFieldsConfig($data['custom_fields']);
                $data['custom_fields_config'] = $customFieldsConfig;
            } else {
                // 커스텀 필드가 비어있거나 삭제된 경우 null로 설정
                $data['custom_fields_config'] = null;
            }
        }

        $result = $board->update($data);


        return $result;
    }

    /**
     * 게시판을 삭제합니다.
     */
    public function deleteBoard(Board $board): bool
    {
        try {
            // 게시판 삭제
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

}
