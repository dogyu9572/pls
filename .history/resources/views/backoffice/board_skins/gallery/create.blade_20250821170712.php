@extends('backoffice.layouts.app')

@section('title', ($board->name ?? '갤러리') . ' - 새 갤러리 작성')

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
            <h6>새 갤러리 작성</h6>
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

            <form action="{{ route('backoffice.board-posts.store', $board->slug ?? 'gallery') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_notice" name="is_notice" value="1" {{ old('is_notice') == '1' ? 'checked' : '' }}>
                        <label for="is_notice" class="board-form-label">공지 등록</label>
                    </div>                    
                </div>

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
                    <textarea class="board-form-control board-form-textarea" id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                </div>

                <!-- 썸네일 이미지 -->
                <div class="board-form-group">
                    <label for="thumbnail" class="board-form-label">썸네일 이미지</label>
                    <div class="image-upload-area" id="thumbnailUploadArea">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="mb-2">썸네일 이미지를 선택하거나 여기로 드래그하세요</p>
                        <p class="text-muted small">권장 크기: 300x200px, 최대 2MB</p>
                        <input type="file" class="board-file-input" id="thumbnail" name="thumbnail" 
                               accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('thumbnail').click()">
                            이미지 선택
                        </button>
                    </div>
                    <div id="thumbnailPreview" class="mt-3" style="display: none;">
                        <img id="thumbnailImage" class="thumbnail-preview" src="" alt="썸네일 미리보기">
                    </div>
                </div>

                <!-- 이미지 갤러리 -->
                <div class="board-form-group">
                    <label for="images" class="board-form-label">이미지 갤러리</label>
                    <div class="image-upload-area" id="galleryUploadArea">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="mb-2">갤러리 이미지들을 선택하거나 여기로 드래그하세요</p>
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
                           value="{{ old('image_alt') }}" placeholder="이미지에 대한 설명을 입력하세요">
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
                                           value="{{ old("custom_fields.{$field['name']}") }}"
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
                                                    {{ old("custom_fields.{$field['name']}") == $option ? 'selected' : '' }}>
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
                                           value="{{ old("custom_fields.{$field['name']}") }}"
                                           @if(isset($field['required']) && $field['required']) required @endif>
                                    @break
                            @endswitch
                        </div>
                    @endforeach
                @endif

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
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
    
    <script>
        // 썸네일 이미지 처리
        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('thumbnailImage').src = e.target.result;
                    document.getElementById('thumbnailPreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // 갤러리 이미지 처리
        document.getElementById('images').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const preview = document.getElementById('galleryPreview');
            preview.innerHTML = '';

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageItem = document.createElement('div');
                    imageItem.className = 'image-item';
                    imageItem.innerHTML = `
                        <img src="${e.target.result}" alt="갤러리 이미지">
                        <button type="button" class="image-remove" onclick="removeGalleryImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(imageItem);
                };
                reader.readAsDataURL(file);
            });
        });

        // 폼 제출 시 이미지 데이터 구조화
        document.querySelector('form').addEventListener('submit', function(e) {
            const imagesInput = document.getElementById('images');
            const files = Array.from(imagesInput.files);
            
            if (files.length > 0) {
                // 이미지 파일들을 FormData에 추가
                files.forEach((file, index) => {
                    const formData = new FormData();
                    formData.append(`gallery_images[${index}]`, file);
                });
            }
        });

        // 갤러리 이미지 제거
        function removeGalleryImage(index) {
            const input = document.getElementById('images');
            const dt = new DataTransfer();
            const { files } = input;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }

            input.files = dt.files;
            // 미리보기 다시 로드
            const event = new Event('change');
            input.dispatchEvent(event);
        }

        // 드래그 앤 드롭
        ['thumbnailUploadArea', 'galleryUploadArea'].forEach(id => {
            const area = document.getElementById(id);
            const input = area.querySelector('input[type="file"]');

            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.classList.add('dragover');
            });

            area.addEventListener('dragleave', () => {
                area.classList.remove('dragover');
            });

            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.classList.remove('dragover');
                
                if (id === 'thumbnailUploadArea') {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change'));
                } else {
                    const dt = new DataTransfer();
                    dt.items.add(...e.dataTransfer.files);
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
@endpush
