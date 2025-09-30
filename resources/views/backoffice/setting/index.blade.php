@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/modal.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/settings.css') }}">
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
                                            <button type="button" class="file-selected-remove" onclick="removeSelectedFavicon()">
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
<script src="{{ asset('js/frontend/file-upload.js') }}"></script>
<script src="{{ asset('js/backoffice/settings.js') }}"></script>

@endsection
