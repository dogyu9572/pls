@extends('backoffice.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/banners.css') }}">
@endsection

@section('title', '배너 수정')

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
                    <h6>배너 수정</h6>
                </div>
                <div class="board-card-body">
                        <form action="{{ route('backoffice.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- 이미지 제거를 위한 숨겨진 필드 -->
                            <input type="hidden" name="remove_desktop_image" id="remove_desktop_image" value="0">
                            <input type="hidden" name="remove_mobile_image" id="remove_mobile_image" value="0">

                        <!-- 1. 배너제목 -->
                        <div class="board-form-group">
                            <label for="title" class="board-form-label">배너제목 <span class="required">*</span></label>
                            <input type="text" class="board-form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $banner->title) }}" required>
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
                                           id="main_text" name="main_text" value="{{ old('main_text', $banner->main_text) }}">
                                    @error('main_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="sub_text" class="board-form-label">서브텍스트</label>
                                    <input type="text" class="board-form-control @error('sub_text') is-invalid @enderror" 
                                           id="sub_text" name="sub_text" value="{{ old('sub_text', $banner->sub_text) }}">
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
                                           id="url" name="url" value="{{ old('url', $banner->url) }}" placeholder="https://example.com">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="video_url" class="board-form-label">영상 URL</label>
                                    <input type="url" class="board-form-control @error('video_url') is-invalid @enderror" 
                                           id="video_url" name="video_url" value="{{ old('video_url', $banner->video_url) }}" placeholder="">
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
                                    <input type="checkbox" name="use_period" value="1" {{ old('use_period', $banner->use_period) ? 'checked' : '' }} id="use_period" class="board-checkbox-input">
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
                                                   id="start_date" name="start_date" value="{{ old('start_date', $banner->start_date ? $banner->start_date->format('Y-m-d') : '') }}">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="board-form-col board-form-col-6">
                                        <div class="board-form-group">
                                            <label for="end_date" class="board-form-label">종료일</label>
                                            <input type="date" class="board-form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date', $banner->end_date ? $banner->end_date->format('Y-m-d') : '') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. 이미지 (데스크톱, 모바일) -->
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
                                        <div class="board-file-preview" id="desktopImagePreview">
                                            @if($banner->desktop_image)
                                                <img src="{{ asset('storage/' . $banner->desktop_image) }}" alt="현재 데스크톱 이미지" class="thumbnail-preview">
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImagePreview('desktop_image', 'desktopImagePreview')">
                                                    <i class="fas fa-trash"></i> 데스크톱 이미지 제거
                                                </button>
                                            @endif
                                        </div>
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
                                        <div class="board-file-preview" id="mobileImagePreview">
                                            @if($banner->mobile_image)
                                                <img src="{{ asset('storage/' . $banner->mobile_image) }}" alt="현재 모바일 이미지" class="thumbnail-preview">
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImagePreview('mobile_image', 'mobileImagePreview')">
                                                    <i class="fas fa-trash"></i> 모바일 이미지 제거
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @error('mobile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 6. 사용여부, 배너순서 -->
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label class="board-form-label">사용여부</label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="is_active_1" name="is_active" value="1" class="board-radio-input" {{ old('is_active', $banner->is_active) == '1' ? 'checked' : '' }}>
                                            <label for="is_active_1">사용</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="is_active_0" name="is_active" value="0" class="board-radio-input" {{ old('is_active', $banner->is_active) == '0' ? 'checked' : '' }}>
                                            <label for="is_active_0">숨김</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="sort_order" class="board-form-label">배너순서</label>
                                    <input type="number" class="board-form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
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
@endsection
