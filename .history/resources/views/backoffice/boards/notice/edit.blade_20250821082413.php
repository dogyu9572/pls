@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 수정')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
        <!-- Bootstrap CSS (Summernote 필요) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        
        <!-- Summernote 커스텀 스타일 - 공식 사이트 스타일 -->
        <style>
            /* Summernote 공식 사이트 스타일 */
            .note-toolbar {
                background: #f8f9fa !important;
                border: 1px solid #e9ecef !important;
                border-bottom: none !important;
                border-radius: 6px 6px 0 0 !important;
                padding: 8px 12px !important;
                margin-bottom: 0 !important;
            }
            
            /* Summernote 전용 스타일 - buttons.css와의 충돌 방지 */
            .note-toolbar .note-btn-group {
                margin-right: 8px !important;
                gap: 0 !important; /* buttons.css의 gap 제거 */
                display: inline-flex !important; /* buttons.css의 flex 제거 */
                flex-wrap: nowrap !important; /* buttons.css의 flex-wrap 제거 */
            }
            
            /* 버튼 기본 스타일 - 두 번째 이미지처럼 */
            .note-toolbar .note-btn {
                background: white !important;
                border: 1px solid #dee2e6 !important;
                color: #333 !important;
                padding: 6px 8px !important;
                transition: all 0.2s ease !important;
                font-size: 14px !important;
                border-radius: 4px !important;
                margin: 0 !important;
                min-width: auto !important;
                flex: none !important; /* buttons.css의 flex 영향 제거 */
            }
            
            /* 버튼 호버 효과 - 부드럽게 */
            .note-toolbar .note-btn:hover {
                background: #f8f9fa !important;
                border-color: #adb5bd !important;
                color: #000 !important;
            }
            
            /* 활성화된 버튼 */
            .note-toolbar .note-btn.active {
                background: #e9ecef !important;
                border-color: #adb5bd !important;
                color: #000 !important;
            }
            
            /* 드롭다운 화살표 색상 - 검은색으로, 위치 조정 */
            .note-toolbar .note-btn-group .note-btn.dropdown-toggle::after {
                border-top-color: #333 !important;                
                position: relative !important;
                top: 1px !important; /* 세로 위치는 유지 */
            }
            
            /* 드롭다운 버튼 영역 넓히기 - 클릭하기 쉽게 */
            .note-toolbar .note-btn-group .note-btn.dropdown-toggle {
                padding-right: 12px !important; /* 오른쪽 패딩 늘려서 화살표 영역 확대 */
                min-width: 30px !important; /* 최소 너비 설정으로 일관성 유지 */
            }
            
            /* 특별히 색상 선택 버튼은 더 넓게 */
            .note-toolbar .note-btn-group .note-btn[data-event="foreColor"],
            .note-toolbar .note-btn-group .note-btn[data-event="backColor"] {
                padding-right: 16px !important;
                min-width: 70px !important;
            }
            
            /* 에디터 프레임 - 공식 사이트와 동일 */
            .note-editor.note-frame {
                border: 1px solid #e9ecef !important;
                border-radius: 6px !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }
            
            /* 에디터 내용 영역 */
            .note-editable {
                padding: 16px !important;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
                line-height: 1.6 !important;
                color: #212529 !important;
                font-size: 14px !important;
            }
            
            /* 드롭다운 메뉴 - 공식 사이트와 동일 */
            .note-dropdown-menu {
                border: 1px solid #dee2e6 !important;
                border-radius: 4px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                background: white !important;
                padding: 4px 0 !important;
            }
            
            .note-dropdown-menu .note-btn {
                background: white !important;
                color: #495057 !important;
                border: none !important;
                padding: 6px 16px !important;
                border-radius: 0 !important;
            }
            
            .note-dropdown-menu .note-btn:hover {
                background: #f8f9fa !important;
                color: #212529 !important;
            }
            
            /* 툴바 배경 - 공식 사이트와 동일한 연한 회색 */
            .note-toolbar {
                background: #f8f9fa !important;
                border: 1px solid #e9ecef !important;
                border-bottom: none !important;
                border-radius: 6px 6px 0 0 !important;
                padding: 8px 10px !important;
                margin-bottom: 0 !important;
            }
            
            /* 그룹 간 구분선 제거 - 깔끔하게 */
            .note-toolbar .note-btn-group:not(:last-child)::after {
                display: none !important;
            }
            
            /* 버튼 그룹 내부는 붙여서 */
            .note-toolbar .note-btn-group .note-btn + .note-btn {
                margin-left: 0 !important;
            }
            
            /* 아이콘 색상 - 검은색으로 */
            .note-toolbar .note-btn i {
                color: #333 !important;
            }
            
            /* 드롭다운 버튼 내부 간격 최적화 */
            .note-toolbar .note-btn-group .note-btn.dropdown-toggle {
                padding-right: 8px !important;
            }
        </style>
@endsection

@push('scripts')
<script>
    // CSRF 토큰을 전역 변수로 설정
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endpush

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
                            <div class="board-existing-files">
                                <h6><i class="fas fa-paperclip"></i> 기존 첨부파일</h6>
                                <div class="board-attachment-list">
                                    @php
                                        $existingAttachments = json_decode($post->attachments, true);
                                    @endphp
                                    @if($existingAttachments && is_array($existingAttachments))
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
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="board-file-preview" id="filePreview"></div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 수정
                    </button>
                    <a href="{{ route('backoffice.board_posts.show', ['notice', $post->id]) }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- jQuery, Bootstrap, Summernote JS (순서 중요!) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    // Summernote 에디터 초기화 (jQuery 로드 확인 후)
    $(document).ready(function() {
        if (typeof $.fn.summernote !== 'undefined') {
            $('#content').summernote({
                height: 400,
                lang: 'ko-KR',                
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'strikethrough', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color', 'forecolor', 'backcolor']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video', 'table']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']]
                ],
                fontNames: ['맑은 고딕', '굴림체', '바탕체', 'Arial', 'Times New Roman', 'Courier New', 'Verdana'],
                fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72'],
                callbacks: {
                    onImageUpload: function(files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadImage(files[i], this);
                        }
                    }
                }
            });
            
            // 드롭다운 이벤트 강제 활성화
            setTimeout(function() {
                $('.note-btn-group .dropdown-toggle').off('click').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).dropdown('toggle');
                });
            }, 500);
            
            console.log('Summernote 에디터가 성공적으로 초기화되었습니다!');
        } else {
            console.error('Summernote가 로드되지 않았습니다!');
            // 폴백: 일반 textarea로 표시
            $('#content').show().attr('style', 'height: 400px; resize: vertical;');
        }
    });

    // 이미지 업로드 함수
    function uploadImage(file, editor) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('/backoffice/upload-image', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.uploaded) {
                $(editor).summernote('insertImage', result.url);
            } else {
                alert('이미지 업로드에 실패했습니다.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('이미지 업로드 중 오류가 발생했습니다.');
        });
    }

    // 첨부파일 미리보기 및 관리
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('attachments');
        const filePreview = document.getElementById('filePreview');
        const fileUpload = document.querySelector('.board-file-upload');
        const maxFiles = 5;
        const maxFileSize = 10 * 1024 * 1024; // 10MB

        // 파일 선택 이벤트 (기존 파일 교체)
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                // 파일 선택 시 기존 파일을 완전히 교체
                replaceAllFiles(files);
            }
        });

        // 드래그 앤 드롭 이벤트
        fileUpload.addEventListener('dragover', function(e) {
            e.preventDefault();
            fileUpload.classList.add('board-file-drag-over');
        });

        fileUpload.addEventListener('dragleave', function(e) {
            e.preventDefault();
            fileUpload.classList.remove('board-file-drag-over');
        });

        fileUpload.addEventListener('drop', function(e) {
            e.preventDefault();
            fileUpload.classList.remove('board-file-drag-over');
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });

        // 파일 교체 함수 (기존 파일을 모두 지우고 새 파일로 교체)
        function replaceAllFiles(files) {
            // 파일 개수 제한 체크
            if (files.length > maxFiles) {
                alert(`최대 ${maxFiles}개까지만 선택할 수 있습니다.`);
                fileInput.value = '';
                return;
            }

            // 파일 크기 체크
            const oversizedFiles = files.filter(file => file.size > maxFileSize);
            if (oversizedFiles.length > 0) {
                alert('10MB 이상인 파일이 있습니다. 10MB 이하의 파일만 선택해주세요.');
                fileInput.value = '';
                return;
            }

            // FileList를 DataTransfer로 변환
            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;

            // 미리보기 생성
            updateFilePreview();
        }

        // 파일 처리 함수 (드래그 앤 드롭용 - 기존 파일에 추가)
        function handleFiles(files) {
            // 파일 개수 제한 체크
            if (files.length > maxFiles) {
                alert(`최대 ${maxFiles}개까지만 선택할 수 있습니다.`);
                return;
            }

            // 파일 크기 체크
            const oversizedFiles = files.filter(file => file.size > maxFileSize);
            if (oversizedFiles.length > 0) {
                alert('10MB 이상인 파일이 있습니다. 10MB 이하의 파일만 선택해주세요.');
                return;
            }

            // 중복 파일 체크 (파일명 기준)
            const existingFiles = Array.from(fileInput.files);
            const newFiles = files.filter(newFile => 
                !existingFiles.some(existingFile => 
                    existingFile.name === newFile.name && 
                    existingFile.size === newFile.size
                )
            );

            if (newFiles.length === 0) {
                alert('이미 추가된 파일입니다.');
                return;
            }

            // 기존 파일과 새 파일 합치기
            const allFiles = [...existingFiles, ...newFiles];
            
            if (allFiles.length > maxFiles) {
                alert(`최대 ${maxFiles}개까지만 선택할 수 있습니다.`);
                return;
            }

            // FileList를 DataTransfer로 변환
            const dt = new DataTransfer();
            allFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;

            // 미리보기 생성
            updateFilePreview();
        }

        // 파일 미리보기 업데이트
        function updateFilePreview() {
            const files = Array.from(fileInput.files);
            filePreview.innerHTML = '';
            
            files.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'board-file-item';
                fileItem.innerHTML = `
                    <div class="board-file-info">
                        <i class="fas fa-file"></i>
                        <span class="board-file-name">${file.name}</span>
                        <span class="board-file-size">(${(file.size / 1024 / 1024).toFixed(2)}MB)</span>
                    </div>
                    <button type="button" class="board-file-remove" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                filePreview.appendChild(fileItem);
            });
        }

        // 파일 제거 함수
        window.removeFile = function(index) {
            const dt = new DataTransfer();
            const files = Array.from(fileInput.files);
            
            files.splice(index, 1);
            files.forEach(file => dt.items.add(file));
            
            fileInput.files = dt.files;
            
            // 미리보기 업데이트
            updateFilePreview();
        };

        // 기존 첨부파일 제거 함수
        window.removeExistingFile = function(index) {
            const existingFiles = document.querySelectorAll('.board-attachment-list .existing-file');
            const targetFile = existingFiles[index];
            const hiddenInput = targetFile.querySelector('input[name="existing_attachments[]"]');
            
            if (hiddenInput) {
                hiddenInput.remove(); // 숨겨진 입력 필드 제거
                targetFile.remove(); // 화면에서 제거
            }
        };
    });
</script>
@endpush
@endsection