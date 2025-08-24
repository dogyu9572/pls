<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoardPostRequest;
use App\Http\Requests\UpdateBoardPostRequest;
use App\Http\Requests\StoreBoardCommentRequest;
use App\Http\Requests\CheckPasswordRequest;
use App\Services\BoardService;
use App\Models\Board;
use App\Models\BoardPost;
use App\Models\BoardComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BoardController extends Controller
{
    protected $boardService;

    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    /**
     * 특정 게시판의 게시글 목록을 표시합니다.
     */
    public function index(Request $request, $slug)
    {
        $board = $this->boardService->getBoard($slug);

        // 읽기 권한 체크
        if (!$this->boardService->canAccessBoard($board, $board->permission_read)) {
            if ($board->permission_read === 'admin') {
                abort(403, '관리자만 접근 가능한 게시판입니다.');
            } elseif ($board->permission_read === 'member') {
                abort(403, '회원만 접근 가능한 게시판입니다.');
            }
        }

        // 검색 파라미터
        $searchParams = [
            'search_field' => $request->search_field,
            'search_query' => $request->search_query,
        ];

        $postsData = $this->boardService->getPosts($board, $searchParams);

        // 스킨 정보 가져오기 (커스텀 스킨 우선, 기본 스킨 대체)
        $skinPath = $board->getSkinViewPath('list');

        return view($skinPath, [
            'board' => $board,
            'posts' => $postsData['posts'],
            'notices' => $postsData['notices']
        ]);
    }

    /**
     * 새 게시글 작성 폼을 표시합니다.
     */
    public function create($slug)
    {
        $board = $this->boardService->getBoard($slug);

        // 쓰기 권한 체크
        if (!$this->boardService->canAccessBoard($board, $board->permission_write)) {
            if ($board->permission_write === 'admin') {
                abort(403, '관리자만 작성 가능한 게시판입니다.');
            } elseif ($board->permission_write === 'member') {
                abort(403, '회원만 작성 가능한 게시판입니다.');
            }
        }

        $skinPath = $board->getSkinViewPath('write');

        return view($skinPath, compact('board'));
    }

    /**
     * 새 게시글을 저장합니다.
     */
    public function store(StoreBoardPostRequest $request, $slug)
    {
        $board = $this->boardService->getBoard($slug);

        // 쓰기 권한 체크
        if (!$this->boardService->canAccessBoard($board, $board->permission_write)) {
            if ($board->permission_write === 'admin') {
                abort(403, '관리자만 작성 가능한 게시판입니다.');
            } elseif ($board->permission_write === 'member') {
                abort(403, '회원만 작성 가능한 게시판입니다.');
            }
        }

        $post = $this->boardService->createPost($board, $request->validated());

        return redirect()->route('boards.show', [$slug, $post->id])
            ->with('success', '게시글이 성공적으로 등록되었습니다.');
    }

    /**
     * 특정 게시글을 표시합니다.
     */
    public function show($slug, $id)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $id);

        // 읽기 권한 체크
        if (!$this->boardService->canAccessBoard($board, $board->permission_read)) {
            if ($board->permission_read === 'admin') {
                abort(403, '관리자만 접근 가능한 게시판입니다.');
            } elseif ($board->permission_read === 'member') {
                abort(403, '회원만 접근 가능한 게시판입니다.');
            }
        }

        // 비밀글 접근 권한 체크
        if ($post->is_secret && !$post->canAccess(Auth::user())) {
            abort(403, '비밀글은 작성자와 관리자만 볼 수 있습니다.');
        }

        // 조회수 증가 (중복 방지를 위해 세션 사용)
        if (!session()->has("post_viewed_{$post->id}")) {
            $this->boardService->incrementViewCount($post);
            session(["post_viewed_{$post->id}" => true]);
        }

        $skinPath = $board->getSkinViewPath('view');

        return view($skinPath, compact('board', 'post'));
    }

    /**
     * 게시글 수정 폼을 표시합니다.
     */
    public function edit($slug, $id)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $id);

        // 수정 권한 체크
        $canEdit = $this->boardService->canEditPost($post);

        if (!$canEdit) {
            // 비회원 게시글의 경우 비밀번호 확인 필요
            if (!$post->user_id) {
                return redirect()->route('boards.password_check', ['slug' => $slug, 'post_id' => $post->id, 'action' => 'edit']);
            }
            abort(403, '이 게시글을 수정할 권한이 없습니다.');
        }

        $skin = $board->skin;
        $skinPath = "boards.skins.{$skin->directory}.write";

        return view($skinPath, compact('board', 'post'));
    }

    /**
     * 게시글을 업데이트합니다.
     */
    public function update(UpdateBoardPostRequest $request, $slug, $id)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $id);

        // 수정 권한 체크
        $canEdit = $this->boardService->canEditPost($post);

        if (!$canEdit) {
            // 비회원 게시글의 경우 세션에서 확인
            if (session()->has("post_can_edit_{$post->id}") && session("post_can_edit_{$post->id}") === true) {
                $canEdit = true;
            }
        }

        if (!$canEdit) {
            abort(403, '이 게시글을 수정할 권한이 없습니다.');
        }

        $data = $request->validated();
        
        // 비밀글 설정
        if (isset($data['is_secret'])) {
            $data['is_secret'] = (bool) $data['is_secret'];
        }

        // 공지 설정 (관리자만 가능)
        if (Auth::check() && Auth::user()->isAdmin() && isset($data['is_notice'])) {
            $data['is_notice'] = (bool) $data['is_notice'];
        }

        $this->boardService->updatePost($post, $data);

        return redirect()->route('boards.show', [$slug, $post->id])
            ->with('success', '게시글이 성공적으로 수정되었습니다.');
    }

    /**
     * 게시글을 삭제합니다.
     */
    public function destroy($slug, $id)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $id);

        // 삭제 권한 체크
        $canDelete = $this->boardService->canDeletePost($post);

        if (!$canDelete) {
            // 비회원 게시글의 경우 세션에서 확인
            if (session()->has("post_can_delete_{$post->id}") && session("post_can_delete_{$post->id}") === true) {
                $canDelete = true;
            }
        }

        if (!$canDelete) {
            abort(403, '이 게시글을 삭제할 권한이 없습니다.');
        }

        $this->boardService->deletePost($post);

        return redirect()->route('boards.index', $slug)
            ->with('success', '게시글이 성공적으로 삭제되었습니다.');
    }

    /**
     * 비회원 비밀번호 확인 폼을 표시합니다.
     */
    public function showPasswordCheckForm($slug, $postId, $action)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $postId);

        return view('boards.password_check', compact('board', 'post', 'action'));
    }

    /**
     * 비회원 비밀번호를 검증합니다.
     */
    public function checkPassword(CheckPasswordRequest $request, $slug, $postId, $action)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $postId);

        if ($this->boardService->verifyPassword($request->validated()['password'], $post->password)) {
            if ($action === 'edit') {
                session(["post_can_edit_{$post->id}" => true]);
                return redirect()->route('boards.edit', [$slug, $post->id]);
            } elseif ($action === 'delete') {
                session(["post_can_delete_{$post->id}" => true]);
                return redirect()->route('boards.show', [$slug, $post->id])
                    ->with('success', '이제 게시글을 삭제할 수 있습니다.');
            }
        }

        throw ValidationException::withMessages([
            'password' => ['비밀번호가 일치하지 않습니다.'],
        ]);
    }

    /**
     * 댓글을 저장합니다.
     */
    public function storeComment(StoreBoardCommentRequest $request, $slug, $postId)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $postId);

        // 댓글 권한 체크
        if (!$this->boardService->canAccessBoard($board, $board->permission_comment)) {
            if ($board->permission_comment === 'admin') {
                abort(403, '관리자만 댓글을 작성할 수 있습니다.');
            } elseif ($board->permission_comment === 'member') {
                abort(403, '회원만 댓글을 작성할 수 있습니다.');
            }
        }

        $this->boardService->createComment($post, $request->validated());

        return redirect()->route('boards.show', [$slug, $post->id])
            ->with('success', '댓글이 성공적으로 등록되었습니다.');
    }

    /**
     * 댓글을 삭제합니다.
     */
    public function destroyComment($slug, $postId, $commentId)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $postId);
        $comment = $this->boardService->getComment($post, $commentId);

        // 삭제 권한 체크
        $canDelete = $this->boardService->canDeleteComment($comment);

        if (!$canDelete) {
            // 비회원 댓글의 경우 세션에서 확인
            if (session()->has("comment_can_delete_{$comment->id}") && session("comment_can_delete_{$comment->id}") === true) {
                $canDelete = true;
            }
        }

        if (!$canDelete) {
            abort(403, '이 댓글을 삭제할 권한이 없습니다.');
        }

        $this->boardService->deleteComment($comment);

        return redirect()->route('boards.show', [$slug, $post->id])
            ->with('success', '댓글이 성공적으로 삭제되었습니다.');
    }

    /**
     * 댓글 비밀번호 확인 폼을 표시합니다.
     */
    public function showCommentPasswordCheckForm($slug, $postId, $commentId)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $postId);
        $comment = $this->boardService->getComment($post, $commentId);

        return view('boards.comment_password_check', compact('board', 'post', 'comment'));
    }

    /**
     * 댓글 비밀번호를 검증합니다.
     */
    public function checkCommentPassword(CheckPasswordRequest $request, $slug, $postId, $commentId)
    {
        $board = $this->boardService->getBoard($slug);
        $post = $this->boardService->getPost($board, $postId);
        $comment = $this->boardService->getComment($post, $commentId);

        if ($this->boardService->verifyPassword($request->validated()['password'], $comment->password)) {
            session(["comment_can_delete_{$comment->id}" => true]);
            return redirect()->route('boards.show', [$slug, $post->id])
                ->with('success', '이제 댓글을 삭제할 수 있습니다.');
        }

        throw ValidationException::withMessages([
            'password' => ['비밀번호가 일치하지 않습니다.'],
        ]);
    }
}
