@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
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
