@extends('backoffice.layouts.app')

@section('title', '기본정보')

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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_title">사이트 타이틀 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('site_title') is-invalid @enderror"
                                           id="site_title" name="site_title" value="{{ old('site_title', $setting->site_title) }}">
                                    @error('site_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_url">사이트 URL <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('site_url') is-invalid @enderror"
                                           id="site_url" name="site_url" value="{{ old('site_url', $setting->site_url) }}">
                                    @error('site_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_email">관리자 이메일 <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('admin_email') is-invalid @enderror"
                                           id="admin_email" name="admin_email" value="{{ old('admin_email', $setting->admin_email) }}">
                                    @error('admin_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">회사명</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name) }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address">회사 주소</label>
                                    <input type="text" class="form-control @error('company_address') is-invalid @enderror"
                                           id="company_address" name="company_address" value="{{ old('company_address', $setting->company_address) }}">
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_tel">회사 연락처</label>
                                    <input type="text" class="form-control @error('company_tel') is-invalid @enderror"
                                           id="company_tel" name="company_tel" value="{{ old('company_tel', $setting->company_tel) }}">
                                    @error('company_tel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo" class="form-label">로고</label>
                                    <div class="file-upload-container">
                                        <div class="file-upload-area" id="fileUploadArea">
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
                                        <div class="file-selected" id="fileSelected">
                                            <div class="file-selected-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="file-selected-name" id="fileName"></div>
                                            <button type="button" class="file-selected-remove" onclick="removeSelectedFile()">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="favicon">파비콘</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror"
                                               id="favicon" name="favicon">
                                        <label class="custom-file-label" for="favicon">파일 선택...</label>
                                        @error('favicon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($setting->favicon_path)
                                        <div class="mt-2">
                                            <img src="{{ $setting->favicon_path }}" alt="파비콘" class="img-thumbnail favicon-preview">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="footer_text">푸터 텍스트</label>
                            <textarea class="form-control footer-textarea @error('footer_text') is-invalid @enderror"
                                      id="footer_text" name="footer_text" rows="8">{{ old('footer_text', $setting->footer_text) }}</textarea>
                            @error('footer_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-submit btn-green">저장</button>
                            <a href="{{ route('backoffice.dashboard') }}" class="btn btn-cancel">취소</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// 파일 업로드 영역 드래그 앤 드롭 기능
document.addEventListener('DOMContentLoaded', function() {
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('logo');
    
    if (fileUploadArea && fileInput) {
        // 드래그 오버 이벤트
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // 드래그 오버 시 스타일 변경
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileUploadArea.classList.add('dragover');
        }
        
        function unhighlight() {
            fileUploadArea.classList.remove('dragover');
        }
        
        // 파일 드롭 시 처리
        fileUploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        }
        
        // 파일 선택 시 처리
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelect(this.files[0]);
            }
        });
        
        // 파일 선택 처리
        function handleFileSelect(file) {
            // 파일 타입 검증
            if (!file.type.startsWith('image/')) {
                alert('이미지 파일만 선택할 수 있습니다.');
                return;
            }
            
            // 파일 크기 검증 (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('파일 크기는 5MB 이하여야 합니다.');
                return;
            }
            
            // 파일명 표시
            showSelectedFile(file.name);
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
            <button type="button" class="file-preview-remove" onclick="removeLogo()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="file-preview-content">
        </div>
    `;
    return container;
}

// 로고 제거
function removeLogo() {
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
@endpush
