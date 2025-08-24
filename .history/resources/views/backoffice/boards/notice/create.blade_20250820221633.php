@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 작성')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<!-- CKEditor 5 Super Build (더 많은 기능 포함) -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/super-build/ckeditor.js"></script>
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
    // SimpleUploadAdapter 클래스 정의
    class SimpleUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => {
                const formData = new FormData();
                formData.append('image', file);
                
                // CSRF 토큰을 FormData에 추가
                formData.append('_token', window.csrfToken);
                
                return fetch('/backoffice/upload-image', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Response text:', text);
                            throw new Error('Invalid JSON response');
                        }
                    });
                })
                .then(result => {
                    if (result.uploaded) {
                        return {
                            default: result.url
                        };
                    } else {
                        throw new Error(result.error.message);
                    }
                });
            });
        }

        abort() {
            // 업로드 중단 처리
        }
    }

    // SimpleUploadAdapterPlugin 정의
    function SimpleUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new SimpleUploadAdapter(loader);
        };
    }

    // CKEditor 5 Super Build 에디터 초기화
    CKEDITOR.ClassicEditor.create(document.querySelector('#content'), {
        language: 'ko',
        height: '400px',
        placeholder: '내용을 입력하세요...',
        extraPlugins: [SimpleUploadAdapterPlugin],
        toolbar: {
            items: [
                'undo', 'redo',
                '|', 'heading',
                '|', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
                '|', 'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript',
                '|', 'link', 'blockQuote', 'insertTable', 'mediaEmbed', 'imageUpload',
                '|', 'bulletedList', 'numberedList',
                '|', 'outdent', 'indent',
                '|', 'alignment', 'horizontalLine',
                '|', 'removeFormat', 'sourceEditing'
            ]
        },
        heading: {
            options: [
                { model: 'paragraph', title: '단락', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: '제목 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: '제목 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: '제목 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: '제목 4', class: 'ck-heading_heading4' },
                { model: 'heading5', view: 'h5', title: '제목 5', class: 'ck-heading_heading5' },
                { model: 'heading6', view: 'h6', title: '제목 6', class: 'ck-heading_heading6' }
            ]
        },
        fontSize: {
            options: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200]
        },
        fontFamily: {
            options: [
                'default',
                'Arial, Helvetica, sans-serif',
                'Arial Black, Gadget, sans-serif',
                'Comic Sans MS, cursive, sans-serif',
                'Courier New, Courier, monospace',
                'Georgia, serif',
                'Impact, Charcoal, sans-serif',
                'Lucida Console, Monaco, monospace',
                'Lucida Sans Unicode, Lucida Grande, sans-serif',
                'Tahoma, Geneva, sans-serif',
                'Times New Roman, Times, serif',
                'Trebuchet MS, Helvetica, sans-serif',
                'Verdana, Geneva, sans-serif',
                '맑은 고딕, Malgun Gothic, sans-serif',
                '궁서체, Batang, serif',
                '굴림체, Gulim, sans-serif',
                '바탕체, Dotum, sans-serif'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells',
                'tableProperties',
                'tableCellProperties'
            ]
        },
        image: {
            upload: {
                types: ['jpeg', 'png', 'gif', 'webp']
            },
            toolbar: [
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                '|',
                'toggleImageCaption',
                'imageTextAlternative',
                '|',
                'linkImage'
            ]
        },
        mediaEmbed: {
            previewsInData: true
        }
    })
        .then(editor => {
            // 에디터 내용이 변경될 때마다 원본 textarea에 반영
            editor.model.document.on('change:data', () => {
                editor.getData();
            });
            
            // 에디터 높이 강제 설정
            const editorElement = editor.ui.view.element;
            if (editorElement) {
                editorElement.style.height = '400px';
                editorElement.style.minHeight = '400px';
            }
        })
        .catch(error => {
            console.error(error);
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