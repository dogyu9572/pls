@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/setting.css') }}">
@endsection

@section('content')
<!-- 알림 모달 -->
<div id="alertModal" class="modal">
    <div class="modal-content">
        <div id="modalHeader" class="modal-header">
            <span id="modalTitle">알림</span>
            <span class="close-modal">&times;</span>
        </div>
        <div id="modalBody" class="modal-body">
            <p id="modalMessage"></p>
        </div>
    </div>
</div>

<div class="board-container">
    <div class="board-header">
        <h1>사이트 설정</h1>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>기본 설정</h6>
        </div>
        <div class="board-card-body">
                    @if(session('success'))
                        <div class="alert alert-success hidden-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('backoffice.setting.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="site_title" class="board-form-label">사이트 타이틀 <span class="required">*</span></label>
                                    <input type="text" class="board-form-control @error('site_title') is-invalid @enderror"
                                           id="site_title" name="site_title" value="{{ old('site_title', $setting->site_title) }}">
                                    @error('site_title')
                                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="site_url" class="board-form-label">사이트 URL <span class="required">*</span></label>
                                    <input type="text" class="board-form-control @error('site_url') is-invalid @enderror"
                                           id="site_url" name="site_url" value="{{ old('site_url', $setting->site_url) }}">
                                    @error('site_url')
                                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="admin_email" class="board-form-label">관리자 이메일 <span class="required">*</span></label>
                                    <input type="email" class="board-form-control @error('admin_email') is-invalid @enderror"
                                           id="admin_email" name="admin_email" value="{{ old('admin_email', $setting->admin_email) }}">
                                    @error('admin_email')
                                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="company_name" class="board-form-label">회사명</label>
                                    <input type="text" class="board-form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name) }}">
                                    @error('company_name')
                                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="company_address" class="board-form-label">회사 주소</label>
                                    <input type="text" class="board-form-control @error('company_address') is-invalid @enderror"
                                           id="company_address" name="company_address" value="{{ old('company_address', $setting->company_address) }}">
                                    @error('company_address')
                                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="company_tel" class="board-form-label">회사 연락처</label>
                                    <input type="text" class="board-form-control @error('company_tel') is-invalid @enderror"
                                           id="company_tel" name="company_tel" value="{{ old('company_tel', $setting->company_tel) }}">
                                    @error('company_tel')
                                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="logo" class="board-form-label">로고</label>
                                    <div class="file-upload-container">
                                        <div class="file-upload-area" id="logoUploadArea">
                                            <div class="file-upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <div class="file-upload-text">
                                                <span class="file-upload-title">클릭하여 파일 선택</span>
                                                <span class="file-upload-subtitle">또는 파일을 여기로 드래그하세요</span>
                                            </div>
                                            <input type="file" class="file-input @error('logo') is-invalid @enderror"
                                                   id="logo" name="logo" accept="image/*">
                                        </div>
                                        
                                        <!-- 파일 선택 완료 후 표시 -->
                                        <div class="file-selected" id="logoSelected">
                                            <div class="file-selected-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="file-selected-name" id="logoFileName"></div>
                                            <button type="button" class="file-selected-remove" onclick="removeSelectedFile('logo')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        @error('logo')
                                            <div class="file-error">{{ $message }}</div>
                                        @enderror
                                        
                                        @if($setting->logo_path)
                                            <div class="file-preview">
                                                <div class="file-preview-header">
                                                    <span class="file-preview-title">현재 로고</span>
                                                    <button type="button" class="file-preview-remove" onclick="removeLogo()">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="file-preview-content">
                                                    <img src="{{ $setting->logo_path }}" alt="로고" class="file-preview-image">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="favicon" class="board-form-label">파비콘</label>
                                    <div class="file-upload-container">
                                        <div class="file-upload-area" id="faviconUploadArea">
                                            <div class="file-upload-icon">
                                                <i class="fas fa-image"></i>
                                            </div>
                                            <div class="file-upload-text">
                                                <span class="file-upload-title">파비콘 선택</span>
                                                <span class="file-upload-subtitle">ICO, PNG 파일 (16x16, 32x32)</span>
                                            </div>
                                            <input type="file" class="file-input @error('favicon') is-invalid @enderror"
                                                   id="favicon" name="favicon" accept=".ico,.png">
                                        </div>
                                        
                                        <!-- 파비콘 파일 선택 완료 후 표시 -->
                                        <div class="file-selected" id="faviconSelected">
                                            <div class="file-selected-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="file-selected-name" id="faviconFileName"></div>
                                            <button type="button" class="file-selected-remove" onclick="removeSelectedFile('favicon')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        @error('favicon')
                                            <div class="file-error">{{ $message }}</div>
                                        @enderror
                                        @if($setting->favicon_path)
                                            <div class="file-preview">
                                                <div class="file-preview-header">
                                                    <span class="file-preview-title">현재 파비콘</span>
                                                    <button type="button" class="file-preview-remove" onclick="removeFavicon()">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="file-preview-content">
                                                    <img src="{{ $setting->favicon_path }}" alt="파비콘" class="file-preview-image" style="max-height: 64px;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <label for="footer_text" class="board-form-label">푸터 텍스트</label>
                            <textarea class="board-form-control board-form-textarea @error('footer_text') is-invalid @enderror"
                                      id="footer_text" name="footer_text" rows="8">{{ old('footer_text', $setting->footer_text) }}</textarea>
                            @error('footer_text')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="board-form-actions">
                            <button type="submit" class="btn btn-primary">저장</button>
                            <a href="{{ route('backoffice.dashboard') }}" class="btn btn-secondary">취소</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// 파일 업로드 영역 드래그 앤 드롭 기능
document.addEventListener('DOMContentLoaded', function() {
    // 로고 파일 업로드 처리
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('logo');
    
    if (fileUploadArea && fileInput) {
        setupFileUpload(fileUploadArea, fileInput, 'logo');
    }
    
    // 파비콘 파일 업로드 처리
    const faviconUploadArea = document.getElementById('faviconUploadArea');
    const faviconInput = document.getElementById('favicon');
    
    if (faviconUploadArea && faviconInput) {
        setupFileUpload(faviconUploadArea, faviconInput, 'favicon');
    }
    
    function setupFileUpload(uploadArea, fileInput, type) {
        // 드래그 오버 이벤트
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // 드래그 오버 시 스타일 변경
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => highlight(uploadArea), false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => unhighlight(uploadArea), false);
        });
        
        function highlight(area) {
            area.classList.add('dragover');
        }
        
        function unhighlight(area) {
            area.classList.remove('dragover');
        }
        
        // 파일 드롭 시 처리
        uploadArea.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0], type);
            }
        }, false);
        
        // 파일 선택 시 처리
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelect(this.files[0], type);
            }
        });
        
        // 파일 선택 처리
        function handleFileSelect(file, type) {
            // 파일 타입 검증
            if (type === 'logo' && !file.type.startsWith('image/')) {
                alert('이미지 파일만 선택할 수 있습니다.');
                return;
            }
            
            if (type === 'favicon' && !(file.type === 'image/x-icon' || file.type === 'image/png' || file.name.endsWith('.ico'))) {
                alert('ICO 또는 PNG 파일만 선택할 수 있습니다.');
                return;
            }
            
            // 파일 크기 검증 (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('파일 크기는 5MB 이하여야 합니다.');
                return;
            }
            
            // 파일명 표시
            if (type === 'logo') {
                showSelectedFile(file.name);
            } else if (type === 'favicon') {
                showSelectedFavicon(file.name);
            }
        }
    }
});

// 파일 선택 완료 표시
function showSelectedFile(fileName) {
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileSelected = document.getElementById('fileSelected');
    const fileNameElement = document.getElementById('fileName');
    
    if (fileUploadArea && fileSelected && fileNameElement) {
        // 파일명 설정
        fileNameElement.textContent = fileName;
        
        // 업로드 영역 숨기기
        fileUploadArea.style.display = 'none';
        
        // 선택된 파일 표시
        fileSelected.classList.add('show');
    }
}

// 파비콘 파일 선택 완료 표시
function showSelectedFavicon(fileName) {
    const faviconUploadArea = document.getElementById('faviconUploadArea');
    const faviconSelected = document.getElementById('faviconSelected');
    const faviconFileNameElement = document.getElementById('faviconFileName');
    
    if (faviconUploadArea && faviconSelected && faviconFileNameElement) {
        // 파일명 설정
        faviconFileNameElement.textContent = fileName;
        
        // 업로드 영역 숨기기
        faviconUploadArea.style.display = 'none';
        
        // 선택된 파일 표시
        faviconSelected.classList.add('show');
    }
}

// 선택된 파일 제거
function removeSelectedFile() {
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileSelected = document.getElementById('fileSelected');
    const fileInput = document.getElementById('logo');
    
    if (fileUploadArea && fileSelected && fileInput) {
        // 파일 입력 초기화
        fileInput.value = '';
        
        // 업로드 영역 다시 표시
        fileUploadArea.style.display = 'block';
        
        // 선택된 파일 숨기기
        fileSelected.classList.remove('show');
    }
}

// 선택된 파비콘 파일 제거
function removeSelectedFavicon() {
    const faviconUploadArea = document.getElementById('faviconUploadArea');
    const faviconSelected = document.getElementById('faviconSelected');
    const faviconInput = document.getElementById('favicon');
    
    if (faviconUploadArea && faviconSelected && faviconInput) {
        // 파일 입력 초기화
        faviconInput.value = '';
        
        // 업로드 영역 다시 표시
        faviconUploadArea.style.display = 'block';
        
        // 선택된 파일 숨기기
        faviconSelected.classList.remove('show');
    }
}

// 기존 로고 파일 삭제
function removeLogo() {
    if (confirm('로고를 삭제하시겠습니까?')) {
        // 숨겨진 입력 필드 추가하여 삭제 요청
        const form = document.querySelector('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_logo';
        input.value = '1';
        form.appendChild(input);
        
        // 폼 제출
        form.submit();
    }
}

// 기존 파비콘 파일 삭제
function removeFavicon() {
    if (confirm('파비콘을 삭제하시겠습니까?')) {
        // 숨겨진 입력 필드 추가하여 삭제 요청
        const form = document.querySelector('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_favicon';
        input.value = '1';
        form.appendChild(input);
        
        // 폼 제출
        form.submit();
    }
}

// 파일 미리보기 생성 (기존 파일용)
function createFilePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewContainer = document.querySelector('.file-preview') || createPreviewContainer();
        const previewContent = previewContainer.querySelector('.file-preview-content');
        
        previewContent.innerHTML = `
            <img src="${e.target.result}" alt="로고 미리보기" class="file-preview-image">
        `;
        
        // 기존 미리보기 제거
        const existingPreview = document.querySelector('.file-preview');
        if (existingPreview && existingPreview !== previewContainer) {
            existingPreview.remove();
        }
        
        // 새 미리보기 추가
        if (!existingPreview) {
            document.querySelector('.file-upload-container').appendChild(previewContainer);
        }
    };
    reader.readAsDataURL(file);
}

// 미리보기 컨테이너 생성
function createPreviewContainer() {
    const container = document.createElement('div');
    container.className = 'file-preview';
    container.innerHTML = `
        <div class="file-preview-header">
            <span class="file-preview-title">새 로고 미리보기</span>
            <button type="button" class="file-preview-remove" onclick="removeNewLogoPreview()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="file-preview-content">
        </div>
    `;
    return container;
}

// 새 로고 미리보기 제거
function removeNewLogoPreview() {
    const fileInput = document.getElementById('logo');
    const previewContainer = document.querySelector('.file-preview');
    
    if (fileInput) {
        fileInput.value = '';
    }
    
    if (previewContainer) {
        previewContainer.remove();
    }
}
</script>
<script src="{{ asset('js/file-upload.js') }}"></script>
<script src="{{ asset('js/backoffice/settings.js') }}"></script>
@endsection
