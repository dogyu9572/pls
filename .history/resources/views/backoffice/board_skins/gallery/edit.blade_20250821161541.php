@extends('backoffice.layouts.app')

@section('title', ($board->name ?? '갤러리') . ' - 갤러리 수정')

@section('styles')
     <link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
    <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .image-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
            transition: border-color 0.3s;
        }
        
        .image-upload-area:hover {
            border-color: #007bff;
        }
        
        .image-upload-area.dragover {
            border-color: #007bff;
            background: #e3f2fd;
        }
        
        .thumbnail-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .image-gallery-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .image-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .image-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255,0,0,0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
        }
        
        .existing-images {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .existing-image-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }
        
        .existing-image-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
        
        .existing-image-info {
            flex: 1;
        }
        
        .existing-image-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .existing-image-size {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
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
                                             class="existing-image-preview">
                                        <div class="existing-image-info">
                                            <div class="existing-image-name">{{ $image['name'] ?? '이미지' }}</div>
                                            <div class="existing-image-size">{{ number_format($image['size'] / 1024 / 1024, 2) }}MB</div>
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
    
    <script>
        // 썸나일 이미지 처리
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
                        <img src="${e.target.result}" alt="새 갤러리 이미지">
                        <button type="button" class="image-remove" onclick="removeNewGalleryImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(imageItem);
                };
                reader.readAsDataURL(file);
            });
        });

        // 새 갤러리 이미지 제거
        function removeNewGalleryImage(index) {
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

        // 기존 썸나일 제거
        function removeThumbnail() {
            if (confirm('현재 썸나일을 제거하시겠습니까?')) {
                // hidden input으로 제거 표시
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_thumbnail';
                hiddenInput.value = '1';
                document.querySelector('form').appendChild(hiddenInput);
                
                // 기존 썸나일 영역 숨기기
                document.querySelector('.existing-images').style.display = 'none';
            }
        }

        // 기존 갤러리 이미지 제거
        function removeExistingImage(index) {
            if (confirm('이 이미지를 제거하시겠습니까?')) {
                // 해당 이미지 항목 제거
                const imageItem = event.target.closest('.existing-image-item');
                imageItem.remove();
                
                // hidden input으로 제거 표시
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_existing_images[]';
                hiddenInput.value = index;
                document.querySelector('form').appendChild(hiddenInput);
            }
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
