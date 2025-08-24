@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 작성')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.boards.posts.index', 'notice') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>게시글 작성</h6>
        </div>
        <div class="board-card-body">
            @if ($errors->any())
                <div class="board-alert board-alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('backoffice.boards.posts.store', 'notice') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="board-form-group">
                    <label for="category" class="board-form-label">카테고리 분류</label>
                    <select class="board-form-control" id="category" name="category">
                        <option value="">카테고리를 선택하세요</option>
                        <option value="일반" {{ old('category') == '일반' ? 'selected' : '' }}>일반</option>
                        <option value="공지" {{ old('category') == '공지' ? 'selected' : '' }}>공지</option>
                        <option value="안내" {{ old('category') == '안내' ? 'selected' : '' }}>안내</option>
                        <option value="이벤트" {{ old('category') == '이벤트' ? 'selected' : '' }}>이벤트</option>
                        <option value="기타" {{ old('category') == '기타' ? 'selected' : '' }}>기타</option>
                    </select>
                </div>

                <div class="board-form-group">
                    <label for="title" class="board-form-label">제목 <span class="required">*</span></label>
                    <input type="text" class="board-form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="board-form-group">
                    <label for="content" class="board-form-label">내용 <span class="required">*</span></label>
                    <textarea class="board-form-control board-form-textarea" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_notice" name="is_notice" value="1" {{ old('is_notice') == '1' ? 'checked' : '' }}>
                        <label for="is_notice" class="board-form-label">공지 등록</label>
                    </div>                    
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <a href="{{ route('backoffice.boards.posts.index', 'notice') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection