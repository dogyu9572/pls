@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 작성')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<!-- Quill Editor (완전 무료) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
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
                    <div id="editor" style="height: 400px;">{{ old('content') }}</div>
                    <textarea class="board-form-control board-form-textarea" id="content" name="content" rows="15" required style="display: none;">{{ old('content') }}</textarea>
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
    // TinyMCE 이미지 업로드 설정
    function setupImageUpload() {
        // 이미지 업로드 시 서버로 전송
        tinymce.init({
            selector: '#content',
            images_upload_url: '/backoffice/upload-image',
            images_upload_handler: function (blobInfo, success, failure) {
                const formData = new FormData();
                formData.append('image', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', window.csrfToken);
                
                fetch('/backoffice/upload-image', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.uploaded) {
                        success(result.url);
                    } else {
                        failure(result.error.message);
                    }
                })
                .catch(error => {
                    failure('이미지 업로드에 실패했습니다: ' + error.message);
                });
            }
        });
    }

    // TinyMCE 에디터 초기화
    tinymce.init({
        selector: '#content',
        language: 'ko_KR',
        height: 400,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
                'bold italic underline strikethrough | fontfamily fontsize | ' +
                'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help',
        fontsize_formats: '8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 26pt 27pt 28pt 29pt 30pt 31pt 32pt 33pt 34pt 35pt 36pt 37pt 38pt 39pt 40pt 41pt 42pt 43pt 44pt 45pt 46pt 47pt 48pt 49pt 50pt 51pt 52pt 53pt 54pt 55pt 56pt 57pt 58pt 59pt 60pt 61pt 62pt 63pt 64pt 65pt 66pt 67pt 68pt 69pt 70pt 71pt 72pt 73pt 74pt 75pt 76pt 77pt 78pt 79pt 80pt 81pt 82pt 83pt 84pt 85pt 86pt 87pt 88pt 89pt 90pt 91pt 92pt 93pt 94pt 95pt 96pt 97pt 98pt 99pt 100pt 101pt 102pt 103pt 104pt 105pt 106pt 107pt 108pt 109pt 110pt 111pt 112pt 113pt 114pt 115pt 116pt 117pt 118pt 119pt 120pt 121pt 122pt 123pt 124pt 125pt 126pt 127pt 128pt 129pt 130pt 131pt 132pt 133pt 134pt 135pt 136pt 137pt 138pt 139pt 140pt 141pt 142pt 143pt 144pt 145pt 146pt 147pt 148pt 149pt 150pt 151pt 152pt 153pt 154pt 155pt 156pt 157pt 158pt 159pt 160pt 161pt 162pt 163pt 164pt 165pt 166pt 167pt 168pt 169pt 170pt 171pt 172pt 173pt 174pt 175pt 176pt 177pt 178pt 179pt 180pt 181pt 182pt 183pt 184pt 185pt 186pt 187pt 188pt 189pt 190pt 191pt 192pt 193pt 194pt 195pt 196pt 197pt 198pt 199pt 200pt',
        font_family_formats: '맑은 고딕=맑은 고딕, Malgun Gothic; 궁서체=궁서체, Batang; 굴림체=굴림체, Gulim; 바탕체=바탕체, Dotum; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
        menubar: false,
        branding: false,
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        setup: function(editor) {
            // 에디터 내용이 변경될 때마다 원본 textarea에 반영
            editor.on('change', function() {
                editor.save();
            });
        }
    });

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