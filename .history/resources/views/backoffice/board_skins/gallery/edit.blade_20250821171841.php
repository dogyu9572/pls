@extends('backoffice.layouts.app')

@section('title', ($board->name ?? '갤러리') . ' - 갤러리 수정')

@section('styles')
     <link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
    <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'gallery') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>갤러리 수정</h6>
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

            <form action="{{ route('backoffice.board-posts.update', [$board->slug ?? 'gallery', $post->id]) }}" method="POST" enctype="multipart/form-data">
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

                <!-- 썸네일 이미지 -->
                <div class="board-form-group">
                    <label for="thumbnail" class="board-form-label">썸네일 이미지</label>
                    
                    @if($post->thumbnail)
                        <div class="existing-images">
                            <h6>현재 썸네일</h6>
                            <div class="existing-image-item">
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" 
                                     alt="현재 썸네일" 
                                     class="existing-image-preview">
                                <div class="existing-image-info">
                                    <div class="existing-image-name">현재 썸네일</div>
                                    <div class="existing-image-size">기존 이미지</div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeThumbnail()">
                                    <i class="fas fa-trash"></i> 제거
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <div class="image-upload-area" id="thumbnailUploadArea">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="mb-2">새 썸네일 이미지를 선택하거나 여기로 드래그하세요</p>
                        <p class="text-muted small">권장 크기: 300x200px, 최대 2MB</p>
                        <input type="file" class="board-file-input" id="thumbnail" name="thumbnail" 
                               accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('thumbnail').click()">
                            이미지 선택
                        </button>
                    </div>
                    <div id="thumbnailPreview" class="mt-3" style="display: none;">
                        <img id="thumbnailImage" class="thumbnail-preview" src="" alt="새 썸나일 미리보기">
                    </div>
                </div>

                <!-- 이미지 갤러리 -->
                <div class="board-form-group">
                    <label for="images" class="board-form-label">이미지 갤러리</label>
                    
                    @if($post->images)
                        <div class="existing-images">
                            <h6>현재 갤러리 이미지들</h6>
                            @php
                                $existingImages = json_decode($post->images, true);
                            @endphp
                            @if($existingImages && is_array($existingImages))
                                @foreach($existingImages as $index => $image)
                                    <div class="existing-image-item">
                                        <img src="{{ asset('storage/' . $image['path']) }}" 
                                             alt="{{ $image['name'] ?? '갤러리 이미지' }}" 
                                             class="existing-image-preview"
                                             onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                        <div class="existing-image-info">
                                            <div class="existing-image-name">{{ $image['name'] ?? '이미지' }}</div>
                                            <div class="existing-image-size">
                                                @if(isset($image['size']))
                                                    {{ number_format($image['size'] / 1024 / 1024, 2) }}MB
                                                @else
                                                    기존 이미지
                                                @endif
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeExistingImage({{ $index }})">
                                            <i class="fas fa-trash"></i> 제거
                                        </button>
                                        <input type="hidden" name="existing_images[]" value="{{ json_encode($image) }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                    
                    <div class="image-upload-area" id="galleryUploadArea">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="mb-2">새 갤러리 이미지들을 선택하거나 여기로 드래그하세요</p>
                        <p class="text-muted small">최대 10장, 각 파일 5MB 이하</p>
                        <input type="file" class="board-file-input" id="images" name="images[]" 
                               multiple accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('images').click()">
                            이미지 선택
                        </button>
                    </div>
                    <div id="galleryPreview" class="image-gallery-preview mt-3"></div>
                </div>

                <!-- 이미지 대체 텍스트 -->
                <div class="board-form-group">
                    <label for="image_alt" class="board-form-label">이미지 대체 텍스트</label>
                    <input type="text" class="board-form-control" id="image_alt" name="image_alt" 
                           value="{{ $post->image_alt ?? '' }}" placeholder="이미지에 대한 설명을 입력하세요">
                    <small class="form-text text-muted">접근성을 위한 이미지 설명입니다.</small>
                </div>

                <!-- 커스텀 필드들 -->
                @if($board->custom_fields)
                    @foreach($board->custom_fields as $field)
                        <div class="board-form-group">
                            <label for="{{ $field['name'] }}" class="board-form-label">
                                {{ $field['label'] }}
                                @if(isset($field['required']) && $field['required'])
                                    <span class="required">*</span>
                                @endif
                            </label>
                            
                            @switch($field['type'])
                                @case('text')
                                    <input type="text" 
                                           class="board-form-control" 
                                           id="{{ $field['name'] }}" 
                                           name="custom_fields[{{ $field['name'] }}]"
                                           value="{{ old("custom_fields.{$field['name']}", $post->custom_fields[$field['name']] ?? '') }}"
                                           @if(isset($field['max_length'])) maxlength="{{ $field['max_length'] }}" @endif
                                           @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
                                           @if(isset($field['required']) && $field['required']) required @endif>
                                    @break
                                    
                                @case('select')
                                    <select class="board-form-control" 
                                            id="{{ $field['name'] }}" 
                                            name="custom_fields[{{ $field['name'] }}]"
                                            @if(isset($field['required']) && $field['required']) required @endif>
                                        @foreach($field['options'] as $option)
                                            <option value="{{ $option }}" 
                                                    {{ old("custom_fields.{$field['name']}", $post->custom_fields[$field['name']] ?? '') == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @break
                                    
                                @case('date')
                                    <input type="date" 
                                           class="board-form-control" 
                                           id="{{ $field['name'] }}" 
                                           name="custom_fields[{{ $field['name'] }}]"
                                           value="{{ old("custom_fields.{$field['name']}", $post->custom_fields[$field['name']] ?? '') }}"
                                           @if(isset($field['required']) && $field['required']) required @endif>
                                    @break
                            @endswitch
                        </div>
                    @endforeach
                @endif

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 수정
                    </button>
                    <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'gallery') }}" class="btn btn-secondary">취소</a>
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
    
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endpush
