@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/board-skins.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
@endsection

@section('content')
<div class="skin-container">
    <div class="skin-header">
        <h1>{{ $boardSkin->name }} 스킨 템플릿 편집</h1>
        <div>
            <a href="{{ route('backoffice.board_skins.edit', $boardSkin) }}" class="skin-btn skin-btn-secondary">
                <i class="fas fa-arrow-left"></i> 스킨 정보로
            </a>
            <a href="{{ route('backoffice.board_skins.index') }}" class="skin-btn skin-btn-secondary">
                <i class="fas fa-list"></i> 스킨 목록
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="skin-form">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="form-label">
                @if ($template == 'list')
                    목록 템플릿 (list.blade.php)
                @elseif ($template == 'view')
                    상세보기 템플릿 (view.blade.php)
                @elseif ($template == 'write')
                    글쓰기 템플릿 (write.blade.php)
                @endif
            </h3>

            <div class="template-selector">
                <a href="{{ route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => 'list']) }}" class="skin-btn {{ $template == 'list' ? 'skin-btn-primary' : 'skin-btn-secondary' }}">
                    목록
                </a>
                <a href="{{ route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => 'view']) }}" class="skin-btn {{ $template == 'view' ? 'skin-btn-primary' : 'skin-btn-secondary' }}">
                    상세보기
                </a>
                <a href="{{ route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => 'write']) }}" class="skin-btn {{ $template == 'write' ? 'skin-btn-primary' : 'skin-btn-secondary' }}">
                    글쓰기
                </a>
            </div>
        </div>

        <form action="{{ route('backoffice.board_skins.update_template', $boardSkin) }}" method="POST">
            @csrf
            <input type="hidden" name="template" value="{{ $template }}">

            <div class="form-group">
                <div class="editor-container">
                    <textarea id="template-editor" name="content" class="form-control">{{ $content }}</textarea>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="skin-btn skin-btn-primary">
                    <i class="fas fa-save"></i> 저장
                </button>
            </div>
        </form>
    </div>

    <div class="skin-form mt-4">
        <h3 class="form-label mb-4">템플릿 도움말</h3>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        사용 가능한 변수
                    </div>
                    <div class="card-body">
                        <h6>공통 변수</h6>
                        <ul>
                            <li><code>$board</code>: 게시판 객체 (이름, 설명, 설정 등)</li>
                            <li><code>$skin</code>: 스킨 객체 (옵션 등)</li>
                        </ul>

                        <h6>목록 템플릿 변수</h6>
                        <ul>
                            <li><code>$posts</code>: 게시글 컬렉션 (페이징 처리됨)</li>
                            <li><code>$posts->links()</code>: 페이지네이션 링크</li>
                        </ul>

                        <h6>상세보기 템플릿 변수</h6>
                        <ul>
                            <li><code>$post</code>: 게시글 객체 (제목, 내용, 작성자 등)</li>
                            <li><code>$post->comments</code>: 댓글 컬렉션</li>
                            <li><code>$post->rootComments</code>: 최상위 댓글 컬렉션</li>
                        </ul>

                        <h6>글쓰기 템플릿 변수</h6>
                        <ul>
                            <li><code>$post</code>: 수정 시 게시글 객체 (없으면 새 글)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        사용 가능한 함수
                    </div>
                    <div class="card-body">
                        <ul>
                            <li><code>route('boards.index', $board->slug)</code>: 게시판 목록 URL</li>
                            <li><code>route('boards.show', [$board->slug, $post->id])</code>: 게시글 상세 URL</li>
                            <li><code>route('boards.create', $board->slug)</code>: 글쓰기 URL</li>
                            <li><code>route('boards.edit', [$board->slug, $post->id])</code>: 글 수정 URL</li>
                            <li><code>route('boards.comments.store', [$board->slug, $post->id])</code>: 댓글 작성 URL</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        댓글 렌더링
                    </div>
                    <div class="card-body">
                        <p>댓글을 렌더링하려면 다음 코드를 사용하세요:</p>
                        <pre><code>@foreach($post->rootComments as $comment)
    @include('boards.partials.comment', ['comment' => $comment])
@endforeach</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js"></script>
<script src="{{ asset('js/board-skins.js') }}"></script>
@endsection
