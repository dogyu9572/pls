@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/boards.css') }}">
<style>
    .board-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .board-header {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .board-header h2 {
        margin-bottom: 5px;
    }

    .board-description {
        color: #666;
    }

    .board-buttons {
        margin: 20px 0;
        display: flex;
        justify-content: space-between;
    }

    .post-table {
        width: 100%;
    }

    .post-table th {
        background-color: #f5f5f5;
        padding: 10px;
    }

    .post-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
    }

    .post-title a {
        color: #333;
        text-decoration: none;
    }

    .post-title a:hover {
        color: #4a69bd;
    }

    .post-notice {
        background-color: #f8f9fa;
    }

    .notice-badge {
        background-color: #e74c3c;
        color: white;
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 3px;
        margin-right: 5px;
    }

    .comment-count {
        color: #e67e22;
        font-size: 0.9em;
    }

    .btn-write {
        background-color: #4a69bd;
        color: white;
        padding: 8px 20px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-write:hover {
        background-color: #1e3799;
        color: white;
        text-decoration: none;
    }

    .empty-posts {
        text-align: center;
        padding: 40px 0;
        color: #888;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="board-container">
                        <div class="board-header">
                            <h2>{{ $board->name }}</h2>
                            <p class="board-description">{{ $board->description }}</p>
                        </div>

                        <div class="board-buttons">
                            <div>
                                <a href="{{ route('backoffice.board_viewer.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> 게시판 목록
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('backoffice.board_viewer.create_post', $board->slug) }}" class="btn-write">
                                    <i class="fas fa-pencil-alt"></i> 글쓰기
                                </a>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($posts->isEmpty())
                            <div class="empty-posts">
                                <i class="far fa-clipboard fa-3x mb-3"></i>
                                <h4>게시글이 없습니다</h4>
                                <p>첫 번째 게시글을 작성해보세요.</p>
                            </div>
                        @else
                            <table class="post-table">
                                <thead>
                                    <tr>
                                        <th width="70">번호</th>
                                        <th>제목</th>
                                        <th width="120">작성자</th>
                                        <th width="100">작성일</th>
                                        <th width="70">조회</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr class="{{ $post->is_notice ? 'post-notice' : '' }}">
                                            <td class="text-center">
                                                @if($post->is_notice)
                                                    <span class="notice-badge">공지</span>
                                                @else
                                                    {{ $post->id }}
                                                @endif
                                            </td>
                                            <td class="post-title">
                                                <a href="{{ route('backoffice.board_viewer.show_post', [$board->slug, $post->id]) }}">
                                                    {{ $post->title }}
                                                    @if($post->comments_count > 0)
                                                        <span class="comment-count">[{{ $post->comments_count }}]</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                {{ $post->author_name ?? ($post->user->name ?? '알 수 없음') }}
                                            </td>
                                            <td class="text-center">{{ $post->created_at->format('Y-m-d') }}</td>
                                            <td class="text-center">{{ $post->view_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
