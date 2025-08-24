<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardPost;
use App\Models\BoardComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BoardService
{
    /**
     * 게시판 정보를 가져옵니다.
     */
    public function getBoard(string $slug): Board
    {
        return Board::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    /**
     * 게시글 목록을 가져옵니다.
     */
    public function getPosts(Board $board, array $searchParams = []): array
    {
        $query = BoardPost::where('board_id', $board->id)->withCount('comments');

        // 검색 처리
        if (!empty($searchParams['search_field']) && !empty($searchParams['search_query'])) {
            $searchField = $searchParams['search_field'];
            $searchQuery = $searchParams['search_query'];

            if ($searchField === 'title') {
                $query->where('title', 'like', "%{$searchQuery}%");
            } elseif ($searchField === 'content') {
                $query->where('content', 'like', "%{$searchQuery}%");
            } elseif ($searchField === 'author') {
                $query->where('author_name', 'like', "%{$searchQuery}%");
            }
        }

        // 공지글과 일반글 분리
        $notices = $query->where('is_notice', true)->get();
        $posts = $query->where('is_notice', false)->orderBy('created_at', 'desc')->paginate($board->list_count);

        return [
            'notices' => $notices,
            'posts' => $posts
        ];
    }

    /**
     * 게시글을 생성합니다.
     */
    public function createPost(Board $board, array $data): BoardPost
    {
        $postData = [
            'board_id' => $board->id,
            'title' => $data['title'],
            'content' => $data['content'],
        ];

        // 회원인 경우
        if (Auth::check()) {
            $postData['user_id'] = Auth::id();
            $postData['author_name'] = Auth::user()->name;
        } else {
            // 비회원인 경우
            $postData['author_name'] = $data['author_name'];
            $postData['password'] = Hash::make($data['password']);
        }

        // 관리자만 공지 설정 가능
        if (Auth::check() && Auth::user()->isAdmin() && isset($data['is_notice'])) {
            $postData['is_notice'] = $data['is_notice'];
        }

        return BoardPost::create($postData);
    }

    /**
     * 게시글을 가져옵니다.
     */
    public function getPost(Board $board, int $postId): BoardPost
    {
        return BoardPost::where('id', $postId)->where('board_id', $board->id)->firstOrFail();
    }

    /**
     * 게시글 조회수를 증가시킵니다.
     */
    public function incrementViewCount(BoardPost $post): void
    {
        $post->incrementViewCount();
    }

    /**
     * 게시글 수정 권한을 확인합니다.
     */
    public function canEditPost(BoardPost $post): bool
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() || Auth::id() === $post->user_id;
        }
        return false;
    }

    /**
     * 게시글 삭제 권한을 확인합니다.
     */
    public function canDeletePost(BoardPost $post): bool
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() || Auth::id() === $post->user_id;
        }
        return false;
    }

    /**
     * 게시글을 업데이트합니다.
     */
    public function updatePost(BoardPost $post, array $data): bool
    {
        $updateData = [
            'title' => $data['title'],
            'content' => $data['content'],
        ];

        // 비밀글 설정
        if (isset($data['is_secret'])) {
            $updateData['is_secret'] = $data['is_secret'];
        }

        // 공지 설정 (관리자만 가능)
        if (Auth::check() && Auth::user()->isAdmin() && isset($data['is_notice'])) {
            $updateData['is_notice'] = $data['is_notice'];
        }

        return $post->update($updateData);
    }

    /**
     * 게시글을 삭제합니다.
     */
    public function deletePost(BoardPost $post): bool
    {
        return $post->delete();
    }

    /**
     * 댓글을 생성합니다.
     */
    public function createComment(BoardPost $post, array $data): BoardComment
    {
        $commentData = [
            'post_id' => $post->id,
            'content' => $data['content'],
        ];

        // 부모 댓글 ID 설정
        if (!empty($data['parent_id'])) {
            $parentComment = BoardComment::where('id', $data['parent_id'])
                ->where('post_id', $post->id)
                ->first();

            if ($parentComment) {
                $commentData['parent_id'] = $parentComment->id;
                $commentData['depth'] = $parentComment->depth + 1;
            }
        }

        // 회원인 경우
        if (Auth::check()) {
            $commentData['user_id'] = Auth::id();
            $commentData['author_name'] = Auth::user()->name;
        } else {
            // 비회원인 경우
            $commentData['author_name'] = $data['author_name'];
            $commentData['password'] = Hash::make($data['password']);
        }

        return BoardComment::create($commentData);
    }

    /**
     * 댓글을 가져옵니다.
     */
    public function getComment(BoardPost $post, int $commentId): BoardComment
    {
        return BoardComment::where('id', $commentId)->where('post_id', $post->id)->firstOrFail();
    }

    /**
     * 댓글 수정 권한을 확인합니다.
     */
    public function canEditComment(BoardComment $comment): bool
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() || Auth::id() === $comment->user_id;
        }
        return false;
    }

    /**
     * 댓글 삭제 권한을 확인합니다.
     */
    public function canDeleteComment(BoardComment $comment): bool
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() || Auth::id() === $comment->user_id;
        }
        return false;
    }

    /**
     * 댓글을 삭제합니다.
     */
    public function deleteComment(BoardComment $comment): bool
    {
        return $comment->delete();
    }

    /**
     * 비밀번호를 검증합니다.
     */
    public function verifyPassword(string $inputPassword, string $hashedPassword): bool
    {
        return Hash::check($inputPassword, $hashedPassword);
    }

    /**
     * 게시판 접근 권한을 확인합니다.
     */
    public function canAccessBoard(Board $board, string $permission): bool
    {
        if ($permission === 'admin') {
            return Auth::check() && Auth::user()->isAdmin();
        } elseif ($permission === 'member') {
            return Auth::check();
        }
        return true; // public
    }
}
