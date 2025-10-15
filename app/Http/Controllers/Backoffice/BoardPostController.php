<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardPostRequest;
use App\Services\Backoffice\BoardPostService;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardPostController extends Controller
{
    protected $boardPostService;

    public function __construct(BoardPostService $boardPostService)
    {
        $this->boardPostService = $boardPostService;
    }

    /**
     * 특정 게시판의 게시글 목록을 표시
     */
    public function index(Request $request, $slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 단일페이지 모드인 경우 특별 처리
        if ($board->is_single_page) {
            $posts = $this->boardPostService->getPosts($slug, $request);
            
            // 기존 게시글이 있으면 수정 페이지로 리다이렉트
            if ($posts->count() > 0) {
                $post = $posts->first();
                return redirect()->route('backoffice.board-posts.edit', [$slug, $post->id]);
            }
            
            // 게시글이 없으면 생성 페이지로 리다이렉트
            return redirect()->route('backoffice.board-posts.create', $slug);
        }
        
        $posts = $this->boardPostService->getPosts($slug, $request);

        // 자동 생성된 뷰 사용
        return view("backoffice.board-posts.{$slug}.index", compact('board', 'posts'));
    }

    /**
     * 게시글 작성 폼 표시
     */
    public function create($slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 단일페이지 모드인 경우 기존 게시글이 있으면 수정 페이지로 리다이렉트
        if ($board->is_single_page) {
            $posts = $this->boardPostService->getPosts($slug, new Request());
            if ($posts->count() > 0) {
                $post = $posts->first();
                return redirect()->route('backoffice.board-posts.edit', [$slug, $post->id]);
            }
        }
        
        // 다음 순서 번호 조회
        $nextSortOrder = $this->boardPostService->getNextSortOrder($slug);
        
        // 자동 생성된 뷰 사용
        return view("backoffice.board-posts.{$slug}.create", compact('board', 'nextSortOrder'));
    }

    /**
     * 게시글 저장
     */
    public function store(BoardPostRequest $request, $slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 유효성 검사는 BoardPostRequest에서 처리됨
        $validated = $request->validated();
        
        // 단일페이지 모드인 경우 기존 게시글이 있으면 업데이트
        if ($board->is_single_page) {
            $posts = $this->boardPostService->getPosts($slug, new Request());
            if ($posts->count() > 0) {
                $post = $posts->first();
                $this->boardPostService->updatePost($slug, $post->id, $validated, $request, $board);
                return redirect()->route('backoffice.board-posts.edit', [$slug, $post->id])
                    ->with('success', '내용이 수정되었습니다.');
            }
        }
        
        // 서비스를 통해 게시글 저장
        $postId = $this->boardPostService->storePost($slug, $validated, $request, $board);

        return redirect()->route('backoffice.board-posts.index', $slug)
            ->with('success', '게시글이 저장되었습니다.');
    }

    /**
     * 게시글 상세보기
     */
    public function show($slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 서비스를 통해 게시글 조회
        $post = $this->boardPostService->getPost($slug, $postId);
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }

        // 자동 생성된 뷰 사용
        return view("backoffice.board-posts.{$slug}.show", compact('board', 'post'));
    }

    /**
     * 게시글 수정 폼 표시
     */
    public function edit($slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 서비스를 통해 게시글 조회
        $post = $this->boardPostService->getPost($slug, $postId);
        
        if (!$post) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }

        // 자동 생성된 뷰 사용
        return view("backoffice.board-posts.{$slug}.edit", compact('board', 'post'));
    }

    /**
     * 게시글 수정
     */
    public function update(BoardPostRequest $request, $slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 유효성 검사는 BoardPostRequest에서 처리됨
        $validated = $request->validated();
        
        // 서비스를 통해 게시글 수정
        $success = $this->boardPostService->updatePost($slug, $postId, $validated, $request, $board);
        
        if (!$success) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }

        // 단일페이지 모드인 경우 특별한 메시지
        $message = $board->is_single_page ? '내용이 수정되었습니다.' : '게시글이 수정되었습니다.';
        
        return redirect()->route('backoffice.board-posts.index', $slug)
            ->with('success', $message);
    }

    /**
     * 게시글 삭제
     */
    public function destroy($slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 서비스를 통해 게시글 삭제
        $success = $this->boardPostService->deletePost($slug, $postId);
        
        if (!$success) {
            abort(404, '게시글을 찾을 수 없습니다.');
        }

        return redirect()->route('backoffice.board-posts.index', $slug)
            ->with('success', '게시글이 삭제되었습니다.');
    }

    /**
     * 게시글 일괄 삭제
     */
    public function bulkDestroy(Request $request, $slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'integer|exists:board_' . $slug . ',id'
        ]);

        $postIds = $request->input('post_ids');
        
        try {
            // 서비스를 통해 일괄 삭제
            $deletedCount = $this->boardPostService->bulkDelete($slug, $postIds);

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

    /**
     * 정렬 순서 업데이트
     */
    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.post_id' => 'required|integer',
            'updates.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            $updates = $request->input('updates');
            
            // 게시판별로 그룹화
            $boardGroups = [];
            foreach ($updates as $update) {
                $postId = $update['post_id'];
                $sortOrder = $update['sort_order'];
                
                // 게시글 ID로 게시판 찾기 (동적 테이블에서 직접 찾기)
                $boardSlug = $this->findBoardSlugByPostId($postId);
                
                if ($boardSlug) {
                    $boardGroups[$boardSlug][] = [
                        'post_id' => $postId,
                        'sort_order' => $sortOrder
                    ];
                }
            }

            // 각 게시판별로 정렬 순서 업데이트
            foreach ($boardGroups as $slug => $posts) {
                $tableName = 'board_' . $slug;
                
                foreach ($posts as $post) {
                    \DB::table($tableName)
                        ->where('id', $post['post_id'])
                        ->update(['sort_order' => $post['sort_order']]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => '정렬 순서가 저장되었습니다.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '정렬 순서 저장 중 오류가 발생했습니다: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 게시글 ID로 게시판 slug 찾기
     */
    private function findBoardSlugByPostId($postId)
    {
        // 모든 board_ 테이블에서 게시글 ID 찾기
        $tables = \DB::select("SHOW TABLES LIKE 'board_%'");
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            if ($tableName === 'boards' || $tableName === 'board_skins' || $tableName === 'board_comments') {
                continue;
            }
            
            $exists = \DB::table($tableName)->where('id', $postId)->exists();
            if ($exists) {
                return str_replace('board_', '', $tableName);
            }
        }
        
        return null;
    }
}
