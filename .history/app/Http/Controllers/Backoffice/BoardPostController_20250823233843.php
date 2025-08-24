<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardPostRequest;
use App\Services\BoardPostService;
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
        // 자동 생성된 뷰 사용
        return view("backoffice.board-posts.{$slug}.create", compact('board'));
    }

    /**
     * 게시글 저장
     */
    public function store(BoardPostRequest $request, $slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        
        // 유효성 검사는 BoardPostRequest에서 처리됨
        $validated = $request->validated();
        
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

        return redirect()->route('backoffice.board-posts.index', $slug)
            ->with('success', '게시글이 수정되었습니다.');
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
}
