@extends('backoffice.layouts.app')

@section('title', '팝업 추가')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/backoffice/popups.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
    <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="board-container">
        <div class="board-header">      
            <a href="{{ route('backoffice.popups.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
            </a>
        </div>

        <div class="board-card">
            <div class="board-card-body">
                <form action="{{ route('backoffice.popups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label for="title" class="board-form-label">팝업제목 <span class="text-danger">*</span></label>
                                <input type="text" class="board-form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label class="board-form-label">게시기간 사용여부</label>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="use_period" value="1" {{ old('use_period', '0') == '1' ? 'checked' : '' }}>
                                        <span class="radio-text">Y</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="use_period" value="0" {{ old('use_period', '0') == '0' ? 'checked' : '' }}>
                                        <span class="radio-text">N</span>
                                    </label>
                                </div>
                                <small class="form-text text-muted">*게시기간을 사용하지 않을시, 상시표출되는 팝업이 생성됩니다.</small>
                            </div>
                        </div>
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

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-6">
                            <div class="board-form-group">
                                <label for="width" class="board-form-label">팝업가로</label>
                                <div class="input-group">
                                    <input type="text" class="board-form-control @error('width') is-invalid @enderror" 
                                           id="width" name="width" value="{{ old('width') }}">
                                    <span class="input-group-text">px</span>
                                </div>
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="board-form-col board-form-col-6">
                            <div class="board-form-group">
                                <label for="height" class="board-form-label">팝업세로</label>
                                <div class="input-group">
                                    <input type="text" class="board-form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height" value="{{ old('height') }}">
                                    <span class="input-group-text">px</span>
                                </div>
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-6">
                            <div class="board-form-group">
                                <label for="position_top" class="board-form-label">팝업위치(Top)</label>
                                <div class="input-group">
                                    <input type="text" class="board-form-control @error('position_top') is-invalid @enderror" 
                                           id="position_top" name="position_top" value="{{ old('position_top') }}">
                                    <span class="input-group-text">px</span>
                                </div>
                                @error('position_top')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="board-form-col board-form-col-6">
                            <div class="board-form-group">
                                <label for="position_left" class="board-form-label">팝업위치(Left)</label>
                                <div class="input-group">
                                    <input type="text" class="board-form-control @error('position_left') is-invalid @enderror" 
                                           id="position_left" name="position_left" value="{{ old('position_left') }}">
                                    <span class="input-group-text">px</span>
                                </div>
                                @error('position_left')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label for="url" class="board-form-label">URL</label>
                                <input type="url" class="board-form-control @error('url') is-invalid @enderror" 
                                       id="url" name="url" value="{{ old('url') }}" placeholder="https://example.com">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label class="board-form-label">타겟</label>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="url_target" value="_blank" {{ old('url_target', '_blank') == '_blank' ? 'checked' : '' }}>
                                        <span class="radio-text">새창</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="url_target" value="_self" {{ old('url_target') == '_self' ? 'checked' : '' }}>
                                        <span class="radio-text">현재창</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label class="board-form-label">팝업표시타입</label>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="popup_display_type" value="normal" {{ old('popup_display_type', 'normal') == 'normal' ? 'checked' : '' }}>
                                        <span class="radio-text">일반팝업</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="popup_display_type" value="layer" {{ old('popup_display_type') == 'layer' ? 'checked' : '' }}>
                                        <span class="radio-text">레이어팝업</span>
                                    </label>
                                </div>
                                <small class="form-text text-muted">*일반팝업: 새창으로 열림, 레이어팝업: 현재 페이지에 오버레이로 표시</small>
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label class="board-form-label">팝업타입</label>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="popup_type" value="image" {{ old('popup_type', 'image') == 'image' ? 'checked' : '' }}>
                                        <span class="radio-text">이미지</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="popup_type" value="html" {{ old('popup_type') == 'html' ? 'checked' : '' }}>
                                        <span class="radio-text">HTML</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 이미지 업로드 섹션 -->
                    <div class="popup-image-section" id="popup_image_section">
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-12">
                                <div class="board-form-group">
                                    <label class="board-form-label">팝업이미지</label>
                                    <div class="board-file-upload">
                                        <div class="board-file-input-wrapper">
                                            <input type="file" class="board-file-input" id="popup_image" name="popup_image" accept=".jpg,.jpeg,.png,.gif">
                                            <div class="board-file-input-content">
                                                <i class="fas fa-image"></i>
                                                <span class="board-file-input-text">팝업 이미지를 선택하거나 여기로 드래그하세요</span>
                                                <span class="board-file-input-subtext">JPG, PNG, GIF 파일만 가능 (최대 5MB)</span>
                                            </div>
                                        </div>
                                        <div class="board-file-preview" id="popupImagePreview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HTML 에디터 섹션 -->
                    <div class="popup-html-section" id="popup_html_section" style="display: none;">
                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-12">
                                <div class="board-form-group">
                                    <label for="popup_content" class="board-form-label">HTML 콘텐츠</label>
                                    <textarea class="board-form-control summernote-editor @error('popup_content') is-invalid @enderror" 
                                              id="popup_content" name="popup_content" rows="10" placeholder="HTML 콘텐츠를 입력하세요">{{ old('popup_content') }}</textarea>
                                    @error('popup_content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label class="board-form-label">사용여부</label>
                                <div class="radio-group">
                                    <label class="radio-label">
                                        <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <span class="radio-text">Y</span>
                                    </label>
                                    <label class="radio-label">
                                        <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                        <span class="radio-text">N</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="board-form-row">
                        <div class="board-form-col board-form-col-12">
                            <div class="board-form-group">
                                <label for="sort_order" class="board-form-label">순서</label>
                                <input type="number" class="board-form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                <small class="form-text text-muted">*높은 숫자일수록 상위에 표출됩니다.</small>
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
                        <a href="{{ route('backoffice.popups.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> 취소
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery, Bootstrap, Summernote JS (순서 중요!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/backoffice/popups.js') }}"></script>
@endsection
