<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\BoardPost;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BoardPostController extends Controller
{
    /**
     * 특정 게시판의 게시글 목록을 표시
     */
    public function index(Request $request, $slug)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블명 생성
        $tableName = 'board_' . $slug;
        
        // 쿼리 빌더 시작
        $query = \Illuminate\Support\Facades\DB::table($tableName);
        
        // 검색 조건 적용
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
                // 전체 검색: 제목과 내용 모두에서 검색
                $query->where(function($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('content', 'like', "%{$keyword}%");
                });
            }
        }
        
        // 정렬 및 페이지네이션
        $posts = $query->orderBy('is_notice', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString(); // 검색 파라미터 유지
        
        // created_at을 Carbon 객체로 변환
        $posts->getCollection()->transform(function ($post) {
            if (isset($post->created_at) && is_string($post->created_at)) {
                $post->created_at = \Carbon\Carbon::parse($post->created_at);
            }
            if (isset($post->updated_at) && is_string($post->updated_at)) {
                $post->updated_at = \Carbon\Carbon::parse($post->updated_at);
            }
            return $post;
        });

        // 자동 생성된 뷰 사용
        return view("backoffice.boards.{$slug}.index", compact('board', 'posts'));
    }

    /**
     * 게시글 작성 폼 표시
     */
    public function create($slug)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        // 자동 생성된 뷰 사용
        return view("backoffice.boards.{$slug}.create", compact('board'));
    }

    /**
     * 게시글 저장
     */
    public function store(Request $request, $slug)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블명 생성
        $tableName = 'board_' . $slug;
        
        // 유효성 검사
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_notice' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // 10MB
        ]);
        
        // HTML 내용 정리 (XSS 방지) - 기본적인 태그만 허용
        $content = strip_tags($validated['content'], '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><table><thead><tbody><tr><td><th><a><img><div><span>');
        
        // 첨부파일 처리
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
        
        // 동적 테이블에 게시글 저장
        $postId = \Illuminate\Support\Facades\DB::table($tableName)->insertGetId([
            'user_id' => auth()->id(),
            'author_name' => auth()->user()->name ?? '관리자',
            'title' => $validated['title'],
            'content' => $content, // 정리된 HTML 내용 사용
            'category' => $validated['category'],
            'is_notice' => $request->has('is_notice'),
            'attachments' => json_encode($attachments),
            'view_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('backoffice.board_posts.index', $slug)
            ->with('success', '게시글이 저장되었습니다.');
    }

    /**
     * 게시글 상세보기
     */
    public function show($slug, $postId)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블에서 게시글 조회
        $tableName = 'board_' . $slug;
        $post = \Illuminate\Support\Facades\DB::table($tableName)->where('id', $postId)->first();
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }
        
        // created_at을 Carbon 객체로 변환
        if (isset($post->created_at) && is_string($post->created_at)) {
            $post->created_at = \Carbon\Carbon::parse($post->created_at);
        }
        if (isset($post->updated_at) && is_string($post->updated_at)) {
            $post->updated_at = \Carbon\Carbon::parse($post->updated_at);
        }
        
        // 자동 생성된 뷰 사용
        return view("backoffice.boards.{$slug}.show", compact('board', 'post'));
    }

    /**
     * 게시글 수정 폼 표시
     */
    public function edit($slug, $postId)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블에서 게시글 조회
        $tableName = 'board_' . $slug;
        $post = \Illuminate\Support\Facades\DB::table($tableName)->where('id', $postId)->first();
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }
        
        // created_at을 Carbon 객체로 변환
        if (isset($post->created_at) && is_string($post->created_at)) {
            $post->created_at = \Carbon\Carbon::parse($post->created_at);
        }
        if (isset($post->updated_at) && is_string($post->updated_at)) {
            $post->updated_at = \Carbon\Carbon::parse($post->updated_at);
        }
        
        // 자동 생성된 뷰 사용
        return view("backoffice.boards.{$slug}.edit", compact('board', 'post'));
    }

    /**
     * 게시글 수정 업데이트
     */
    public function update(Request $request, $slug, $postId)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블명 생성
        $tableName = 'board_' . $slug;
        
        // 게시글 조회
        $post = \Illuminate\Support\Facades\DB::table($tableName)->where('id', $postId)->first();
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }
        
        // 유효성 검사
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
            'is_notice' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // 10MB
        ]);
        
        // HTML 내용 정리 (XSS 방지) - 기본적인 태그만 허용
        $content = strip_tags($validated['content'], '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><table><thead><tbody><tr><td><th><a><img><div><span>');
        
        // 첨부파일 처리
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
        
        // 기존 첨부파일이 있고 새 첨부파일이 없는 경우 기존 파일 유지
        if (empty($attachments) && $post->attachments) {
            $attachments = json_decode($post->attachments, true);
        }
        
        // 게시글 업데이트
        \Illuminate\Support\Facades\DB::table($tableName)
            ->where('id', $postId)
            ->update([
                'title' => $validated['title'],
                'content' => $content,
                'category' => $validated['category'],
                'is_notice' => $request->has('is_notice'),
                'attachments' => json_encode($attachments),
                'updated_at' => now()
            ]);
        
        return redirect()->route('backoffice.board_posts.show', [$slug, $postId])
            ->with('success', '게시글이 수정되었습니다.');
    }

    /**
     * 게시글 삭제
     */
    public function destroy($slug, $postId)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블명 생성
        $tableName = 'board_' . $slug;
        
        // 게시글 조회
        $post = \Illuminate\Support\Facades\DB::table($tableName)->where('id', $postId)->first();
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }
        
        // 첨부파일이 있으면 실제 파일도 삭제
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
        
        // 게시글 삭제
        \Illuminate\Support\Facades\DB::table($tableName)
            ->where('id', $postId)
            ->delete();
        
        return redirect()->route('backoffice.board_posts.index', $slug)
            ->with('success', '게시글이 삭제되었습니다.');
    }

    /**
     * 게시글 일괄 삭제
     */
    public function bulkDestroy(Request $request, $slug)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'integer|exists:board_' . $slug . ',id'
        ]);

        $postIds = $request->input('post_ids');
        $tableName = 'board_' . $slug;
        
        try {
            // 선택된 게시글들 삭제
            $deletedCount = \Illuminate\Support\Facades\DB::table($tableName)
                ->whereIn('id', $postIds)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . '개의 게시글이 삭제되었습니다.',
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ], 500);
        }
    }
}
