<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardSkinRequest;
use App\Http\Requests\UpdateBoardSkinRequest;
use App\Services\BoardSkinService;
use App\Models\BoardSkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BoardSkinController extends Controller
{
    protected $boardSkinService;

    public function __construct(BoardSkinService $boardSkinService)
    {
        $this->boardSkinService = $boardSkinService;
    }

    /**
     * 게시판 스킨 목록을 표시합니다.
     */
    public function index()
    {
        $skins = $this->boardSkinService->getAllSkins();
        return view('backoffice.board_skins.index', compact('skins'));
    }

    /**
     * 스킨 생성 폼을 표시합니다.
     */
    public function create()
    {
        return view('backoffice.board_skins.create');
    }

    /**
     * 새 스킨을 저장합니다.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'directory' => 'required|alpha_dash|max:50|unique:board_skins',
            'description' => 'nullable|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'nullable|json',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // 썸네일 이미지 처리
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/board_skins');
            $data['thumbnail'] = Storage::url($path);
        }

        // 기본 스킨으로 설정된 경우 다른 스킨의 기본값을 해제
        if (isset($data['is_default']) && $data['is_default']) {
            BoardSkin::where('is_default', true)->update(['is_default' => false]);
        }

        // 스킨 디렉토리 생성
        $skinPath = resource_path('views/boards/skins/' . $data['directory']);
        if (!File::exists($skinPath)) {
            File::makeDirectory($skinPath, 0755, true);
        }

        // 스킨 생성
        $skin = BoardSkin::create($data);

        // 기본 스킨 템플릿 파일 생성 (list.blade.php, view.blade.php, write.blade.php)
        $this->createDefaultTemplates($skin);

        return redirect()->route('backoffice.board_skins.index')
            ->with('success', '게시판 스킨이 성공적으로 생성되었습니다.');
    }

    /**
     * 특정 스킨 정보를 표시합니다.
     */
    public function show(BoardSkin $boardSkin)
    {
        return view('backoffice.board_skins.show', compact('boardSkin'));
    }

    /**
     * 스킨 수정 폼을 표시합니다.
     */
    public function edit(BoardSkin $boardSkin)
    {
        return view('backoffice.board_skins.edit', compact('boardSkin'));
    }

    /**
     * 스킨 정보를 업데이트합니다.
     */
    public function update(Request $request, BoardSkin $boardSkin)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'directory' => ['required', 'alpha_dash', 'max:50', Rule::unique('board_skins')->ignore($boardSkin->id)],
            'description' => 'nullable|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'nullable|json',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // 썸네일 이미지 처리
        if ($request->hasFile('thumbnail')) {
            // 기존 이미지 삭제
            if ($boardSkin->thumbnail) {
                $oldPath = str_replace('/storage/', 'public/', $boardSkin->thumbnail);
                Storage::delete($oldPath);
            }
            // 새 이미지 저장
            $path = $request->file('thumbnail')->store('public/board_skins');
            $data['thumbnail'] = Storage::url($path);
        }

        // 기본 스킨으로 설정된 경우 다른 스킨의 기본값을 해제
        if (isset($data['is_default']) && $data['is_default'] && !$boardSkin->is_default) {
            BoardSkin::where('is_default', true)->update(['is_default' => false]);
        }

        // 디렉토리명이 변경된 경우 처리
        if ($boardSkin->directory !== $data['directory']) {
            $oldPath = resource_path('views/boards/skins/' . $boardSkin->directory);
            $newPath = resource_path('views/boards/skins/' . $data['directory']);

            // 디렉토리가 존재하면 이름 변경
            if (File::exists($oldPath)) {
                File::moveDirectory($oldPath, $newPath);
            } else {
                // 존재하지 않으면 생성
                File::makeDirectory($newPath, 0755, true);
                $this->createDefaultTemplates($data['directory']);
            }
        }

        // 스킨 정보 업데이트
        $boardSkin->update($data);

        return redirect()->route('backoffice.board_skins.index')
            ->with('success', '게시판 스킨이 성공적으로 업데이트되었습니다.');
    }

    /**
     * 스킨을 삭제합니다.
     */
    public function destroy(BoardSkin $boardSkin)
    {
        // 기본 스킨인 경우 삭제 불가
        if ($boardSkin->is_default) {
            return redirect()->route('backoffice.board_skins.index')
                ->with('error', '기본 스킨은 삭제할 수 없습니다.');
        }

        // 사용 중인 게시판이 있는지 확인
        $usedCount = $boardSkin->boards()->count();
        if ($usedCount > 0) {
            return redirect()->route('backoffice.board_skins.index')
                ->with('error', '해당 스킨을 사용 중인 게시판이 있어 삭제할 수 없습니다.');
        }

        // 스킨 디렉토리 삭제
        $skinPath = resource_path('views/boards/skins/' . $boardSkin->directory);
        if (File::exists($skinPath)) {
            File::deleteDirectory($skinPath);
        }

        // 썸네일 이미지 삭제
        if ($boardSkin->thumbnail) {
            $thumbnailPath = str_replace('/storage/', 'public/', $boardSkin->thumbnail);
            Storage::delete($thumbnailPath);
        }

        // 스킨 삭제
        $boardSkin->delete();

        return redirect()->route('backoffice.board_skins.index')
            ->with('success', '게시판 스킨이 성공적으로 삭제되었습니다.');
    }

    /**
     * 스킨 템플릿 편집기를 표시합니다.
     */
    public function editTemplate(Request $request, BoardSkin $boardSkin)
    {
        $template = $request->query('template', 'list');
        $validTemplates = ['list', 'view', 'write'];

        if (!in_array($template, $validTemplates)) {
            $template = 'list';
        }

        $filename = $template . '.blade.php';
        $filepath = resource_path('views/boards/skins/' . $boardSkin->directory . '/' . $filename);

        $content = '';
        if (File::exists($filepath)) {
            $content = File::get($filepath);
        } else {
            // 기본 템플릿 내용 가져오기
            $defaultContent = $this->getDefaultTemplateContent($template);
            $content = $defaultContent;
            // 파일 생성
            File::put($filepath, $content);
        }

        return view('backoffice.board_skins.edit_template', compact('boardSkin', 'template', 'content'));
    }

    /**
     * 스킨 템플릿을 저장합니다.
     */
    public function updateTemplate(Request $request, BoardSkin $boardSkin)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|in:list,view,write',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $template = $request->template;
        $content = $request->content;
        $filename = $template . '.blade.php';
        $filepath = resource_path('views/boards/skins/' . $boardSkin->directory . '/' . $filename);

        // 디렉토리가 없으면 생성
        $directory = resource_path('views/boards/skins/' . $boardSkin->directory);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // 파일 저장
        File::put($filepath, $content);

        return redirect()->route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => $template])
            ->with('success', '템플릿이 성공적으로 저장되었습니다.');
    }

    /**
     * 기본 스킨 템플릿을 생성합니다.
     */
    private function createDefaultTemplates($skin)
    {
        $directory = is_object($skin) ? $skin->directory : $skin;
        $skinPath = resource_path('views/boards/skins/' . $directory);

        // 디렉토리가 없으면 생성
        if (!File::exists($skinPath)) {
            File::makeDirectory($skinPath, 0755, true);
        }

        // 기본 목록 템플릿 생성
        $listContent = $this->getDefaultTemplateContent('list');
        File::put($skinPath . '/list.blade.php', $listContent);

        // 기본 상세보기 템플릿 생성
        $viewContent = $this->getDefaultTemplateContent('view');
        File::put($skinPath . '/view.blade.php', $viewContent);

        // 기본 글쓰기 템플릿 생성
        $writeContent = $this->getDefaultTemplateContent('write');
        File::put($skinPath . '/write.blade.php', $writeContent);
    }

    /**
     * 기본 템플릿 내용을 가져옵니다.
     */
    private function getDefaultTemplateContent($template)
    {
        switch ($template) {
            case 'list':
                return <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="board-container">
    <div class="board-header">
        <h2>{{ $board->name }}</h2>
        <p>{{ $board->description }}</p>
    </div>

    <div class="board-search">
        <form action="{{ route('boards.index', $board->slug) }}" method="GET">
            <select name="search_field">
                <option value="title" {{ request('search_field') == 'title' ? 'selected' : '' }}>제목</option>
                <option value="content" {{ request('search_field') == 'content' ? 'selected' : '' }}>내용</option>
                <option value="author" {{ request('search_field') == 'author' ? 'selected' : '' }}>작성자</option>
            </select>
            <input type="text" name="search_query" value="{{ request('search_query') }}">
            <button type="submit">검색</button>
        </form>
    </div>

    <div class="board-list">
        <table>
            <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>작성일</th>
                    <th>조회</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        <a href="{{ route('boards.show', [$board->slug, $post->id]) }}">
                            @if($post->is_notice)
                                <span class="notice-badge">[공지]</span>
                            @endif
                            {{ $post->title }}
                            @if($post->comments_count > 0)
                                <span class="comment-count">[{{ $post->comments_count }}]</span>
                            @endif
                        </a>
                    </td>
                    <td>{{ $post->author_name }}</td>
                    <td>{{ $post->created_at->format('Y-m-d') }}</td>
                    <td>{{ $post->view_count }}</td>
                </tr>
                @endforeach

                @if($posts->isEmpty())
                <tr>
                    <td colspan="5" class="empty-list">게시물이 없습니다.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="board-pagination">
        {{ $posts->links() }}
    </div>

    <div class="board-buttons">
        @if(auth()->check() || $board->permission_write == 'all')
            <a href="{{ route('boards.create', $board->slug) }}" class="btn-write">글쓰기</a>
        @endif
    </div>
</div>
@endsection
EOT;

            case 'view':
                return <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="board-container">
    <div class="board-header">
        <h2>{{ $board->name }}</h2>
    </div>

    <div class="post-view">
        <div class="post-header">
            <h3>{{ $post->title }}</h3>
            <div class="post-info">
                <span class="author">{{ $post->author_name }}</span>
                <span class="date">{{ $post->created_at->format('Y-m-d H:i') }}</span>
                <span class="views">조회 {{ $post->view_count }}</span>
            </div>
        </div>

        <div class="post-content">
            {!! $post->content !!}
        </div>

        <div class="post-buttons">
            <a href="{{ route('boards.index', $board->slug) }}" class="btn-list">목록</a>

            @if(auth()->check() && (auth()->id() == $post->user_id || auth()->user()->isAdmin()))
                <a href="{{ route('boards.edit', [$board->slug, $post->id]) }}" class="btn-edit">수정</a>
                <form class="delete-form" action="{{ route('boards.destroy', [$board->slug, $post->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</button>
                </form>
            @endif
        </div>

        <div class="comments-section">
            <h4>댓글 {{ $post->comments->count() }}개</h4>

            <div class="comments-list">
                @foreach($post->rootComments as $comment)
                    @include('boards.partials.comment', ['comment' => $comment])
                @endforeach
            </div>

            @if(auth()->check() || $board->permission_comment == 'all')
                <div class="comment-form">
                    <form action="{{ route('boards.comments.store', [$board->slug, $post->id]) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="3" placeholder="댓글을 입력하세요..." required></textarea>

                        @guest
                            <div class="guest-info">
                                <input type="text" name="author_name" placeholder="이름" required>
                                <input type="password" name="password" placeholder="비밀번호" required>
                            </div>
                        @endguest

                        <button type="submit">댓글 작성</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
EOT;

            case 'write':
                return <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="board-container">
    <div class="board-header">
        <h2>{{ $board->name }}</h2>
        <h3>{{ isset($post) ? '글 수정' : '글 작성' }}</h3>
    </div>

    <div class="post-form">
        <form action="{{ isset($post) ? route('boards.update', [$board->slug, $post->id]) : route('boards.store', $board->slug) }}" method="POST">
            @csrf
            @if(isset($post))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="title">제목</label>
                <input type="text" id="title" name="title" value="{{ isset($post) ? $post->title : old('title') }}" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            @if(auth()->user() && auth()->user()->isAdmin())
                <div class="form-group">
                    <label for="is_notice">공지글</label>
                    <input type="checkbox" id="is_notice" name="is_notice" value="1" {{ (isset($post) && $post->is_notice) ? 'checked' : '' }}>
                </div>
            @endif

            <div class="form-group">
                <label for="content">내용</label>
                <textarea id="content" name="content" rows="10" required>{{ isset($post) ? $post->content : old('content') }}</textarea>
                @error('content')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            @guest
                <div class="form-group">
                    <label for="author_name">이름</label>
                    <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}" required>
                    @error('author_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            @endguest

            <div class="form-group">
                <label for="is_secret">비밀글</label>
                <input type="checkbox" id="is_secret" name="is_secret" value="1" {{ (isset($post) && $post->is_secret) ? 'checked' : '' }}>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-submit">{{ isset($post) ? '수정하기' : '작성하기' }}</button>
                <a href="{{ route('boards.index', $board->slug) }}" class="btn-cancel">취소</a>
            </div>
        </form>
    </div>
</div>
@endsection
EOT;

            default:
                return '';
        }
    }
}
