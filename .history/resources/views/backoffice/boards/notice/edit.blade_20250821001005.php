@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 수정')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>공지사항 - 게시글 수정</h4>
                    <a href="{{ route('backoffice.board_posts.show', ['notice', $post->id]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> 상세보기
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('backoffice.boards.posts.update', ['notice', $post->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">내용 <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required>{{ $post->content }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_notice" name="is_notice" value="1" {{ $post->is_notice ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_notice">
                                    공지사항으로 등록
                                </label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 수정
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection