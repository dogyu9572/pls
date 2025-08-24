@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 작성')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
        <!-- Bootstrap CSS (Summernote 필요) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
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

            <form action="{{ route('backoffice.board_posts.store', 'notice') }}" method="POST" enctype="multipart/form-data">
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
                        <div class="board-file-preview" id="filePreview"></div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <a href="{{ route('backoffice.board_posts.index', 'notice') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Summernote 에디터 초기화 (jQuery 로드 확인 후)
    $(document).ready(function() {
        if (typeof $.fn.summernote !== 'undefined') {
            $('#content').summernote({
                height: 400,
                lang: 'ko-KR',
                placeholder: '내용을 입력하세요...',
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
    });
</script>
@endpush
@endsection