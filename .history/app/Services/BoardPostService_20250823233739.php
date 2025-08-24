<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $tableName = $this->getTableName($slug);
        $query = DB::table($tableName);

        // 검색 조건 적용
        $this->applySearchFilters($query, $request);

        // 정렬 및 페이지네이션
        $posts = $query->orderBy('is_notice', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // 날짜 변환
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

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $searchType = $request->search_type;

            if ($searchType === 'title') {
                $query->where('title', 'like', "%{$keyword}%");
            } elseif ($searchType === 'content') {
                $query->where('content', 'like', "%{$keyword}%");
            } else {
                // 전체 검색
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('content', 'like', "%{$keyword}%");
                });
            }
        }
    }

    /**
     * 날짜 변환
     */
    private function transformDates($posts): void
    {
        $posts->getCollection()->transform(function ($post) {
            if (isset($post->created_at) && is_string($post->created_at)) {
                $post->created_at = Carbon::parse($post->created_at);
            }
            if (isset($post->updated_at) && is_string($post->updated_at)) {
                $post->updated_at = Carbon::parse($post->updated_at);
            }
            return $post;
        });
    }

    /**
     * 게시글 저장
     */
    public function storePost(string $slug, array $validated, Request $request, $board): int
    {
        $tableName = $this->getTableName($slug);

        // HTML 내용 정리
        $content = $this->sanitizeContent($validated['content']);

        // 썸네일 처리
        $thumbnail = $this->handleThumbnail($request, $slug);

        // 첨부파일 처리
        $attachments = $this->handleAttachments($request, $slug);

        // 커스텀 필드 처리
        $customFields = $this->processCustomFields($request, $board);

        // 게시글 저장
        return DB::table($tableName)->insertGetId([
            'user_id' => null,
            'author_name' => '관리자',
            'title' => $validated['title'],
            'content' => $content,
            'category' => $validated['category'],
            'is_notice' => $request->has('is_notice'),
            'thumbnail' => $thumbnail,
            'attachments' => json_encode($attachments),
            'custom_fields' => !empty($customFields) ? json_encode($customFields) : null,
            'view_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * HTML 내용 정리
     */
    private function sanitizeContent(string $content): string
    {
        return strip_tags($content, '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><table><thead><tbody><tr><td><th><a><img><div><span>');
    }

    /**
     * 썸네일 처리
     */
    private function handleThumbnail(Request $request, string $slug): ?string
    {
        if ($request->hasFile('thumbnail')) {
            return $request->file('thumbnail')->store('thumbnails/' . $slug, 'public');
        }
        return null;
    }

    /**
     * 첨부파일 처리
     */
    private function handleAttachments(Request $request, string $slug): array
    {
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('uploads/' . $slug, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }
        return $attachments;
    }

    /**
     * 커스텀 필드 처리
     */
    private function processCustomFields(Request $request, $board): array
    {
        $customFields = [];
        if ($board->custom_fields_config && is_array($board->custom_fields_config)) {
            foreach ($board->custom_fields_config as $fieldConfig) {
                $fieldName = $fieldConfig['name'];
                $fieldValue = $request->input("custom_field_{$fieldName}");
                $customFields[$fieldName] = $fieldValue;
            }
        }
        return $customFields;
    }

    /**
     * 게시글 조회
     */
    public function getPost(string $slug, int $postId)
    {
        $tableName = $this->getTableName($slug);
        $post = DB::table($tableName)->where('id', $postId)->first();

        if (!$post) {
            return null;
        }

        // 날짜 변환
        if (isset($post->created_at) && is_string($post->created_at)) {
            $post->created_at = Carbon::parse($post->created_at);
        }
        if (isset($post->updated_at) && is_string($post->updated_at)) {
            $post->updated_at = Carbon::parse($post->updated_at);
        }

        return $post;
    }

    /**
     * 게시글 수정
     */
    public function updatePost(string $slug, int $postId, array $validated, Request $request, $board): bool
    {
        $tableName = $this->getTableName($slug);

        // HTML 내용 정리
        $content = $this->sanitizeContent($validated['content']);

        // 썸네일 처리
        $thumbnail = $this->handleThumbnail($request, $slug);

        // 첨부파일 처리
        $attachments = $this->handleAttachments($request, $slug);

        // 커스텀 필드 처리
        $customFields = $this->processCustomFields($request, $board);

        // 게시글 업데이트
        return DB::table($tableName)
            ->where('id', $postId)
            ->update([
                'title' => $validated['title'],
                'content' => $content,
                'category' => $validated['category'],
                'is_notice' => $request->has('is_notice'),
                'thumbnail' => $thumbnail,
                'attachments' => json_encode($attachments),
                'custom_fields' => !empty($customFields) ? json_encode($customFields) : null,
                'updated_at' => now()
            ]);
    }

    /**
     * 게시글 삭제
     */
    public function deletePost(string $slug, int $postId): bool
    {
        $tableName = $this->getTableName($slug);
        
        // 게시글 조회
        $post = DB::table($tableName)->where('id', $postId)->first();
        
        if (!$post) {
            return false;
        }

        // 첨부파일 삭제
        $this->deleteAttachments($post);

        // 게시글 삭제
        return DB::table($tableName)->where('id', $postId)->delete();
    }

    /**
     * 첨부파일 삭제
     */
    private function deleteAttachments($post): void
    {
        if ($post->attachments) {
            $attachments = json_decode($post->attachments, true);
            if (is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    if (isset($attachment['path'])) {
                        $filePath = storage_path('app/public/' . $attachment['path']);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }
        }
    }

    /**
     * 일괄 삭제
     */
    public function bulkDelete(string $slug, array $postIds): int
    {
        $tableName = $this->getTableName($slug);
        
        // 선택된 게시글들 삭제
        return DB::table($tableName)->whereIn('id', $postIds)->delete();
    }
}
