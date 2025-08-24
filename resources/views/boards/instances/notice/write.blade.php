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