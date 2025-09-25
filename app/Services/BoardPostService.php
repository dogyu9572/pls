<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BoardPostService
{
    /**
     * 동적 테이블명 생성
     */
    public function getTableName(string $slug): string
    {
        return 'board_' . $slug;
    }

    /**
     * 게시글 목록 조회
     */
    public function getPosts(string $slug, Request $request)
    {
        $query = DB::table($this->getTableName($slug));
        
        $this->applySearchFilters($query, $request);
        
        // 목록 개수 설정
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 15;
        
        // 정렬 기능이 활성화된 게시판인지 확인
        $board = \App\Models\Board::where('slug', $slug)->first();
        if ($board && $board->enable_sorting) {
            // 정렬 기능이 활성화된 경우: sort_order 우선 (큰 숫자가 위에), 그 다음 공지글, 그 다음 생성일
            $posts = $query->orderBy('sort_order', 'desc')
                ->orderBy('is_notice', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            // 정렬 기능이 비활성화된 경우: 기존 정렬 방식
            $posts = $query->orderBy('is_notice', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        }

        $this->transformDates($posts);
        return $posts;
    }

    /**
     * 검색 필터 적용
     */
    private function applySearchFilters($query, Request $request): void
    {
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('keyword')) {
            $this->applyKeywordSearch($query, $request->keyword, $request->search_type);
        }
    }

    /**
     * 키워드 검색 적용
     */
    private function applyKeywordSearch($query, string $keyword, ?string $searchType): void
    {
        if ($searchType === 'title') {
            $query->where('title', 'like', "%{$keyword}%");
        } elseif ($searchType === 'content') {
            $query->where('content', 'like', "%{$keyword}%");
        } else {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            });
        }
    }

    /**
     * 날짜 변환
     */
    private function transformDates($posts): void
    {
        $posts->getCollection()->transform(function ($post) {
            foreach (['created_at', 'updated_at'] as $dateField) {
                if (isset($post->$dateField) && is_string($post->$dateField)) {
                    $post->$dateField = Carbon::parse($post->$dateField);
                }
            }
            return $post;
        });
    }

    /**
     * 게시글 저장
     */
    public function storePost(string $slug, array $validated, Request $request, $board): int
    {
        $data = $this->preparePostData($validated, $request, $slug, $board);
        
        return DB::table($this->getTableName($slug))->insertGetId($data);
    }

    /**
     * 게시글 데이터 준비
     */
    private function preparePostData(array $validated, Request $request, string $slug, $board): array
    {
        return [
            'user_id' => null,
            'author_name' => '관리자',
            'title' => $validated['title'],
            'content' => $this->sanitizeContent($validated['content']),
            'category' => $validated['category'],
            'is_notice' => $request->has('is_notice'),
            'thumbnail' => $this->handleThumbnail($request, $slug),
            'attachments' => json_encode($this->handleAttachments($request, $slug)),
            'custom_fields' => $this->getCustomFieldsJson($request, $board),
            'view_count' => 0,
            'sort_order' => $request->input('sort_order', 0),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * HTML 내용 정리
     */
    private function sanitizeContent(string $content): string
    {
        $allowedTags = '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><table><thead><tbody><tr><td><th><a><img><div><span>';
        return strip_tags($content, $allowedTags);
    }

    /**
     * 썸네일 처리
     */
    private function handleThumbnail(Request $request, string $slug): ?string
    {
        return $request->hasFile('thumbnail') 
            ? $request->file('thumbnail')->store('thumbnails/' . $slug, 'public')
            : null;
    }

    /**
     * 첨부파일 처리
     */
    private function handleAttachments(Request $request, string $slug): array
    {
        if (!$request->hasFile('attachments')) {
            return [];
        }

        $attachments = [];
        foreach ($request->file('attachments') as $file) {
            $attachments[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $file->store('uploads/' . $slug, 'public'),
                'size' => $file->getSize(),
                'type' => $file->getMimeType()
            ];
        }
        return $attachments;
    }

    /**
     * 커스텀 필드 JSON 생성
     */
    private function getCustomFieldsJson(Request $request, $board): ?string
    {
        $customFields = $this->processCustomFields($request, $board);
        return !empty($customFields) ? json_encode($customFields) : null;
    }

    /**
     * 커스텀 필드 처리
     */
    private function processCustomFields(Request $request, $board): array
    {
        if (!$board->custom_fields_config || !is_array($board->custom_fields_config)) {
            return [];
        }

        $customFields = [];
        foreach ($board->custom_fields_config as $fieldConfig) {
            $fieldName = $fieldConfig['name'];
            $customFields[$fieldName] = $request->input("custom_field_{$fieldName}");
        }
        return $customFields;
    }

    /**
     * 게시글 조회
     */
    public function getPost(string $slug, int $postId)
    {
        $post = DB::table($this->getTableName($slug))->where('id', $postId)->first();
        
        if (!$post) {
            return null;
        }

        $this->transformSinglePostDates($post);
        return $post;
    }

    /**
     * 단일 게시글 날짜 변환
     */
    private function transformSinglePostDates($post): void
    {
        foreach (['created_at', 'updated_at'] as $dateField) {
            if (isset($post->$dateField) && is_string($post->$dateField)) {
                $post->$dateField = Carbon::parse($post->$dateField);
            }
        }
    }

    /**
     * 게시글 수정
     */
    public function updatePost(string $slug, int $postId, array $validated, Request $request, $board): bool
    {
        $data = $this->prepareUpdateData($validated, $request, $slug, $board);
        
        return DB::table($this->getTableName($slug))
            ->where('id', $postId)
            ->update($data);
    }

    /**
     * 수정 데이터 준비
     */
    private function prepareUpdateData(array $validated, Request $request, string $slug, $board): array
    {
        return [
            'title' => $validated['title'],
            'content' => $this->sanitizeContent($validated['content']),
            'category' => $validated['category'],
            'is_notice' => $request->has('is_notice'),
            'thumbnail' => $this->handleThumbnail($request, $slug),
            'attachments' => json_encode($this->handleAttachments($request, $slug)),
            'custom_fields' => $this->getCustomFieldsJson($request, $board),
            'sort_order' => $request->input('sort_order', 0),
            'updated_at' => now()
        ];
    }

    /**
     * 게시글 삭제
     */
    public function deletePost(string $slug, int $postId): bool
    {
        $post = DB::table($this->getTableName($slug))->where('id', $postId)->first();
        
        if (!$post) {
            return false;
        }

        $this->deleteAttachments($post);
        
        return DB::table($this->getTableName($slug))->where('id', $postId)->delete();
    }

    /**
     * 첨부파일 삭제
     */
    private function deleteAttachments($post): void
    {
        if (!$post->attachments) {
            return;
        }

        $attachments = json_decode($post->attachments, true);
        if (!is_array($attachments)) {
            return;
        }

        foreach ($attachments as $attachment) {
            if (isset($attachment['path'])) {
                $filePath = storage_path('app/public/' . $attachment['path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }

    /**
     * 일괄 삭제
     */
    public function bulkDelete(string $slug, array $postIds): int
    {
        return DB::table($this->getTableName($slug))->whereIn('id', $postIds)->delete();
    }
}
