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