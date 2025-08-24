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