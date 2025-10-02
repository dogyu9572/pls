@extends('backoffice.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/banners.css') }}">
@endsection

@section('title', '배너 추가')

@section('content')
<div class="board-container">
    <div class="board-header">      
        <a href="{{ route('backoffice.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="board-alert board-alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="board-card">
                <div class="board-card-header">
                    <h6>배너 추가</h6>
                </div>
                <div class="board-card-body">
                    <form action="{{ route('backoffice.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- 1. 배너제목 -->
                        <div class="board-form-group">
                            <label for="title" class="board-form-label">배너제목 <span class="required">*</span></label>
                            <input type="text" class="board-form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 2. 메인텍스트, 서브텍스트 -->
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="main_text" class="board-form-label">메인텍스트</label>
                                    <input type="text" class="board-form-control @error('main_text') is-invalid @enderror" 
                                           id="main_text" name="main_text" value="{{ old('main_text') }}">
                                    @error('main_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="sub_text" class="board-form-label">서브텍스트</label>
                                    <input type="text" class="board-form-control @error('sub_text') is-invalid @enderror" 
                                           id="sub_text" name="sub_text" value="{{ old('sub_text') }}">
                                    @error('sub_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 3. URL, 영상 URL -->
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="url" class="board-form-label">URL</label>
                                    <input type="url" class="board-form-control @error('url') is-invalid @enderror" 
                                           id="url" name="url" value="{{ old('url') }}" placeholder="https://example.com">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="video_url" class="board-form-label">영상 URL</label>
                                    <input type="url" class="board-form-control @error('video_url') is-invalid @enderror" 
                                           id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 4. 게시기간 설정 -->
                        <div class="board-form-group">
                            <label class="board-form-label">게시기간 설정</label>
                            <div class="board-checkbox-group">
                                <div class="board-checkbox-item">
                                    <input type="checkbox" name="use_period" value="1" {{ old('use_period') ? 'checked' : '' }} id="use_period" class="board-checkbox-input">
                                    <label for="use_period">게시기간 사용</label>
                                </div>
                            </div>
                            <small class="board-form-text">*게시기간을 사용하지 않을시, 상시 표출되는 배너가 생성됩니다.</small>
                        </div>

                        <div class="period-fields" id="period_fields" style="display: none;">
                            <div class="board-form-row">
                                <div class="board-form-col board-form-col-6">
                                        <div class="board-form-group">
                                            <label for="start_date" class="board-form-label">시작일</label>
                                            <input type="date" class="board-form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="board-form-col board-form-col-6">
                                        <div class="board-form-group">
                                            <label for="end_date" class="board-form-label">종료일</label>
                                            <input type="date" class="board-form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. 배너 타입 선택 -->
                        <div class="board-form-group">
                            <label class="board-form-label">배너 타입 <span class="required">*</span></label>
                            <div class="board-radio-group">
                                <div class="board-radio">
                                    <input type="radio" id="banner_type_image" name="banner_type" value="image" 
                                           {{ old('banner_type', 'image') == 'image' ? 'checked' : '' }}>
                                    <label for="banner_type_image">이미지</label>
                                </div>
                                <div class="board-radio">
                                    <input type="radio" id="banner_type_video" name="banner_type" value="video" 
                                           {{ old('banner_type') == 'video' ? 'checked' : '' }}>
                                    <label for="banner_type_video">영상</label>
                                </div>
                            </div>
                            @error('banner_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 6. 이미지 (데스크톱, 모바일) -->
                        <div id="image_fields" class="board-form-section">
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="desktop_image" class="board-form-label">이미지 (데스크톱)</label>
                                    <div class="board-file-upload">
                                        <div class="board-file-input-wrapper">
                                            <input type="file" class="board-file-input" 
                                                   id="desktop_image" name="desktop_image" accept=".jpg,.jpeg,.png,.gif">
                                            <div class="board-file-input-content">
                                                <i class="fas fa-image"></i>
                                                <span class="board-file-input-text">데스크톱 이미지를 선택하거나 여기로 드래그하세요</span>
                                                <span class="board-file-input-subtext">JPG, PNG, GIF 파일만 가능 (최대 5MB)</span>
                                            </div>
                                        </div>
                                        <div class="board-file-preview" id="desktopImagePreview"></div>
                                    </div>
                                    @error('desktop_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="mobile_image" class="board-form-label">이미지 (모바일)</label>
                                    <div class="board-file-upload">
                                        <div class="board-file-input-wrapper">
                                            <input type="file" class="board-file-input" 
                                                   id="mobile_image" name="mobile_image" accept=".jpg,.jpeg,.png,.gif">
                                            <div class="board-file-input-content">
                                                <i class="fas fa-mobile-alt"></i>
                                                <span class="board-file-input-text">모바일 이미지를 선택하거나 여기로 드래그하세요</span>
                                                <span class="board-file-input-subtext">JPG, PNG, GIF 파일만 가능 (최대 5MB)</span>
                                            </div>
                                        </div>
                                        <div class="board-file-preview" id="mobileImagePreview"></div>
                                    </div>
                                    @error('mobile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- 7. 영상 업로드 -->
                        <div id="video_fields" class="board-form-section" style="display: none;">
                            <div class="board-form-row">
                                <div class="board-form-col board-form-col-6">
                                    <div class="board-form-group">
                                        <label for="video_file" class="board-form-label">영상 파일</label>
                                        <div class="board-file-upload">
                                            <div class="board-file-input-wrapper">
                                                <input type="file" class="board-file-input" 
                                                       id="video_file" name="video_file" accept=".mp4,.avi,.mov,.wmv">
                                                <div class="board-file-input-content">
                                                    <i class="fas fa-video"></i>
                                                    <span class="board-file-input-text">영상 파일을 선택하거나 여기로 드래그하세요</span>
                                                    <span class="board-file-input-subtext">MP4, AVI, MOV, WMV 파일만 가능 (최대 10MB)</span>
                                                </div>
                                            </div>
                                            <div class="board-file-preview" id="videoFilePreview"></div>
                                        </div>
                                        @error('video_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="board-form-col board-form-col-6">
                                    <div class="board-form-group">
                                        <label for="video_duration" class="board-form-label">재생 시간 (초)</label>
                                        <input type="number" class="board-form-control @error('video_duration') is-invalid @enderror" 
                                               id="video_duration" name="video_duration" value="{{ old('video_duration') }}" 
                                               min="1" max="300" placeholder="예: 5">
                                        @error('video_duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- 8. 사용여부, 배너순서 -->
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label class="board-form-label">사용여부</label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="is_active_1" name="is_active" value="1" class="board-radio-input" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                            <label for="is_active_1">사용</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="is_active_0" name="is_active" value="0" class="board-radio-input" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                            <label for="is_active_0">숨김</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="sort_order" class="board-form-label">배너순서</label>
                                    <input type="number" class="board-form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    <small class="board-form-text">*숫자가 높을수록 상위에 노출됩니다.</small>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="board-form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 저장
                            </button>
                            <a href="{{ route('backoffice.banners.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> 취소
                            </a>
                        </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/banners.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 배너 타입 토글 초기화
    const bannerTypeRadios = document.querySelectorAll('input[name="banner_type"]');
    const imageFields = document.getElementById('image_fields');
    const videoFields = document.getElementById('video_fields');
    
    if (!bannerTypeRadios.length || !imageFields || !videoFields) {
        return;
    }
    
    // 초기 상태 설정
    toggleBannerTypeFields();
    
    // 라디오 버튼 변경 이벤트
    bannerTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleBannerTypeFields);
    });
    
    function toggleBannerTypeFields() {
        const selectedType = document.querySelector('input[name="banner_type"]:checked')?.value;
        
        if (selectedType === 'video') {
            imageFields.style.display = 'none';
            videoFields.style.display = 'block';
        } else {
            imageFields.style.display = 'block';
            videoFields.style.display = 'none';
        }
    }
    
    // 파일 제거 함수 정의
    window.removeImagePreview = function(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        if (input) input.value = '';
        if (preview) preview.innerHTML = '';
        
        // 서버에 파일 제거 요청을 위한 숨겨진 필드 설정
        if (inputId === 'desktop_image') {
            const removeField = document.getElementById('remove_desktop_image');
            if (removeField) removeField.value = '1';
        } else if (inputId === 'mobile_image') {
            const removeField = document.getElementById('remove_mobile_image');
            if (removeField) removeField.value = '1';
        } else if (inputId === 'video_file') {
            const removeField = document.getElementById('remove_video_file');
            if (removeField) removeField.value = '1';
        }
    };
});
</script>
@endsection
