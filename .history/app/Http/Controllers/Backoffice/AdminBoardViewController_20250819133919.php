<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BoardPost;
use App\Models\BoardComment;
use Illuminate\Http\Request;

class AdminBoardViewController extends Controller
{
    /**
     * 관리자 화면에서 게시판 목록 표시
     */
    public function index()
    {
        $boards = Board::orderBy('created_at', 'desc')->get();
        return view('backoffice.board_viewer.index', compact('boards'));
    }

    /**
     * 관리자 화면에서 특정 게시판의 게시글 목록 표시
     */
    public function showBoard($slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        $posts = BoardPost::where('board_id', $board->id)
                          ->orderBy('is_notice', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('backoffice.board_viewer.show_board', compact('board', 'posts'));
    }

    /**
     * 관리자 화면에서 게시글 상세 보기
     */
    public function showPost($slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        $post = BoardPost::where('board_id', $board->id)
                         ->where('id', $postId)
                         ->firstOrFail();

        // 조회수 증가
        $post->increment('view_count');

        // 댓글 가져오기
        $comments = BoardComment::where('post_id', $post->id)
                               ->where('parent_id', null)
                               ->orderBy('created_at', 'asc')
                               ->with('replies')
                               ->get();

        return view('backoffice.board_viewer.show_post', compact('board', 'post', 'comments'));
    }

    /**
     * 관리자 화면에서 게시글 작성 페이지 표시
     */
    public function createPost($slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        return view('backoffice.board_viewer.create_post', compact('board'));
    }

    /**
     * 게시글 저장
     */
    public function storePost(Request $request, $slug)
    {
        $board = Board::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_notice' => 'sometimes|boolean',
            'is_secret' => 'sometimes|boolean'
        ]);

        $post = new BoardPost();
        $post->board_id = $board->id;
        $post->user_id = auth()->id();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->is_notice = isset($validated['is_notice']) ? $validated['is_notice'] : false;
        $post->is_secret = isset($validated['is_secret']) ? $validated['is_secret'] : false;
        $post->save();

        return redirect()->route('backoffice.board_viewer.show_post', [$slug, $post->id])
                         ->with('success', '게시글이 성공적으로 등록되었습니다.');
    }

    /**
     * 게시글 수정 페이지
     */
    public function editPost($slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        $post = BoardPost::where('board_id', $board->id)
                         ->where('id', $postId)
                         ->firstOrFail();

        return view('backoffice.board_viewer.edit_post', compact('board', 'post'));
    }

    /**
     * 게시글 업데이트
     */
    public function updatePost(Request $request, $slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        $post = BoardPost::where('board_id', $board->id)
                         ->where('id', $postId)
                         ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_notice' => 'sometimes|boolean',
            'is_secret' => 'sometimes|boolean'
        ]);

        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->is_notice = isset($validated['is_notice']) ? $validated['is_notice'] : false;
        $post->is_secret = isset($validated['is_secret']) ? $validated['is_secret'] : false;
        $post->save();

        return redirect()->route('backoffice.board_viewer.show_post', [$slug, $post->id])
                         ->with('success', '게시글이 성공적으로 수정되었습니다.');
    }

    /**
     * 게시글 삭제
     */
    public function destroyPost($slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        $post = BoardPost::where('board_id', $board->id)
                         ->where('id', $postId)
                         ->firstOrFail();

        // 댓글 삭제
        BoardComment::where('post_id', $postId)->delete();

        // 게시글 삭제
        $post->delete();

        return redirect()->route('backoffice.board_viewer.show_board', $slug)
                         ->with('success', '게시글이 성공적으로 삭제되었습니다.');
    }

    /**
     * 댓글 저장
     */
    public function storeComment(Request $request, $slug, $postId)
    {
        $board = Board::where('slug', $slug)->firstOrFail();
        $post = BoardPost::where('board_id', $board->id)
                         ->where('id', $postId)
                         ->firstOrFail();

        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:board_comments,id'
        ]);

        $comment = new BoardComment();
        $comment->post_id = $post->id;
        $comment->user_id = auth()->id();
        $comment->content = $validated['content'];
        $comment->parent_id = $validated['parent_id'] ?? null;
        $comment->save();

        return redirect()->back()->with('success', '댓글이 성공적으로 등록되었습니다.');
    }

    /**
     * 댓글 삭제
     */
    public function destroyComment($slug, $postId, $commentId)
    {
        $comment = BoardComment::findOrFail($commentId);

        // 대댓글이 있는 경우 내용만 삭제 표시
        if ($comment->replies()->count() > 0) {
            $comment->content = '삭제된 댓글입니다.';
            $comment->is_deleted = true;
            $comment->save();
        } else {
            $comment->delete();
        }

        return redirect()->back()->with('success', '댓글이 삭제되었습니다.');
    }
}
