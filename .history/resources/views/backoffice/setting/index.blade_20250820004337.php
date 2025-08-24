@extends('backoffice.layouts.app')

@section('title', '기본설정')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
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
                    <h4>기본설정</h4>
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
<script src="{{ asset('js/file-upload.js') }}"></script>
<script src="{{ asset('js/backoffice/settings.js') }}"></script>
@endpush
