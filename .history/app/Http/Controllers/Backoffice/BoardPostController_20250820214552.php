<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\BoardPost;
use Illuminate\Http\Request;

class BoardPostController extends Controller
{
    /**
     * 특정 게시판의 게시글 목록을 표시
     */
    public function index($slug)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 동적 테이블명 생성
        $tableName = 'board_' . $slug;
        
        // 동적 테이블에서 게시글 조회
        $posts = \Illuminate\Support\Facades\DB::table($tableName)
            ->orderBy('is_notice', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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
            'content' => $validated['content'],
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
    public function show($slug, BoardPost $post)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        // 자동 생성된 뷰 사용
        return view("backoffice.boards.{$slug}.show", compact('board', 'post'));
    }

    /**
     * 게시글 수정 폼 표시
     */
    public function edit($slug, BoardPost $post)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        // 자동 생성된 뷰 사용
        return view("backoffice.boards.{$slug}.edit", compact('board', 'post'));
    }

    /**
     * 게시글 수정 업데이트
     */
    public function update(Request $request, $slug, BoardPost $post)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 게시글 업데이트 처리 (실제 구현 필요)
        // $post->update([...]);
        
        return redirect()->route('backoffice.boards.posts.show', [$slug, $post->id])
            ->with('success', '게시글이 수정되었습니다.');
    }

    /**
     * 게시글 삭제
     */
    public function destroy($slug, BoardPost $post)
    {
        $board = \App\Models\Board::where('slug', $slug)->firstOrFail();
        
        // 게시글 삭제 처리 (실제 구현 필요)
        // $post->delete();
        
        return redirect()->route('backoffice.board_posts.index', $slug)
            ->with('success', '게시글이 삭제되었습니다.');
    }
}
