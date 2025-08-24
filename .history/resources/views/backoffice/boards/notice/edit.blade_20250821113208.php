@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 수정')

@section('styles')

    <link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
    <!-- Bootstrap CSS (Summernote 필요) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board_posts.index', 'notice') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>게시글 수정</h6>
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

            <form action="{{ route('backoffice.board_posts.update', ['notice', $post->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_notice" name="is_notice" value="1" {{ $post->is_notice ? 'checked' : '' }}>
                        <label for="is_notice" class="board-form-label">공지 등록</label>
                    </div>                    
                </div>

                <div class="board-form-group">
                    <label for="category" class="board-form-label">카테고리 분류</label>
                    <select class="board-form-control" id="category" name="category">
                        <option value="">카테고리를 선택하세요</option>
                        <option value="일반" {{ $post->category == '일반' ? 'selected' : '' }}>일반</option>
                        <option value="공지" {{ $post->category == '공지' ? 'selected' : '' }}>공지</option>
                        <option value="안내" {{ $post->category == '안내' ? 'selected' : '' }}>안내</option>
                        <option value="이벤트" {{ $post->category == '이벤트' ? 'selected' : '' }}>이벤트</option>
                        <option value="기타" {{ $post->category == '기타' ? 'selected' : '' }}>기타</option>
                    </select>
                </div>

                <div class="board-form-group">
                    <label for="title" class="board-form-label">제목 <span class="required">*</span></label>
                    <input type="text" class="board-form-control" id="title" name="title" value="{{ $post->title }}" required>
                </div>

                <div class="board-form-group">
                    <label for="content" class="board-form-label">내용 <span class="required">*</span></label>
                    <textarea class="board-form-control board-form-textarea" id="content" name="content" rows="15" required>{{ $post->content }}</textarea>
                </div>                

                <div class="board-form-group">
                    <label class="board-form-label">첨부파일</label>
                    <div class="board-file-upload">
                        <div class="board-file-input-wrapper">
                            <input type="file" class="board-file-input" id="attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar">
                            <div class="board-file-input-content">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span class="board-file-input-text">파일을 선택하거나 여기로 드래그하세요</span>
                                <span class="board-file-input-subtext">최대 5개, 각 파일 10MB 이하</span>
                            </div>
                        </div>
                        
                        @if($post->attachments)
                            @php
                                $existingAttachments = json_decode($post->attachments, true);
                            @endphp
                            @if($existingAttachments && is_array($existingAttachments) && count($existingAttachments) > 0)
                                <div class="board-existing-files">
                                    <div class="board-attachment-list">
                                        @foreach($existingAttachments as $index => $attachment)
                                            <div class="board-attachment-item existing-file" data-index="{{ $index }}">
                                                <i class="fas fa-file"></i>
                                                <span class="board-attachment-name">{{ $attachment['name'] }}</span>
                                                <span class="board-attachment-size">({{ number_format($attachment['size'] / 1024 / 1024, 2) }}MB)</span>
                                                <button type="button" class="board-attachment-remove" onclick="removeExistingFile({{ $index }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="existing_attachments[]" value="{{ json_encode($attachment) }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        <div class="board-file-preview" id="filePreview"></div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 수정
                    </button>
                    <a href="{{ route('backoffice.board_posts.index', 'notice') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- jQuery, Bootstrap, Summernote JS (순서 중요!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/backoffice/board-post-form.js') }}"></script>
@endpush