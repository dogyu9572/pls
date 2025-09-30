@extends('backoffice.layouts.app')

@section('title', ($board->name ?? '게시판'))

@section('styles')
     <link rel="stylesheet" href="{{ asset('css/backoffice/summernote-custom.css') }}">
    <!-- Summernote CSS (Bootstrap 기반, 완전 무료) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/backoffice/business-sections.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'notice') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>Contact us 작성</h6>
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

            <form action="{{ route('backoffice.board-posts.store', $board->slug ?? 'notice') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($board->isNoticeEnabled())
                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" 
                               class="board-checkbox-input" 
                               id="is_notice" 
                               name="is_notice" 
                               value="1" 
                               {{ old('is_notice') == '1' ? 'checked' : '' }}>
                        <label for="is_notice" class="board-form-label">
                            <i class="fas fa-bullhorn"></i> 공지글
                        </label>
                    </div>
                    <small class="board-form-text">체크하면 공지글로 설정되어 최상단에 표시됩니다.</small>
                </div>
                @endif

                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="category" value="일반">
                </div>

                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="title" value="Contact us">
                    <input type="hidden" name="content" value="Contact us 내용">
                </div>

                @if($board->enable_sorting)
                <div class="board-form-group">
                    <label for="sort_order" class="board-form-label">정렬 순서</label>
                    <input type="number" class="board-form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                    <small class="board-form-text">숫자가 작을수록 위에 표시됩니다. (0이면 자동 정렬)</small>
                </div>
                @endif

                <!-- 경영지원 인사총무팀 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[경영지원 인사총무팀]</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_hr_manager" class="board-form-label">담당자 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_hr_manager" name="custom_field_hr_manager" value="{{ old('custom_field_hr_manager', '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_hr_phone" class="board-form-label">전화 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_hr_phone" name="custom_field_hr_phone" value="{{ old('custom_field_hr_phone', '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_hr_email" class="board-form-label">E-MAIL <span class="required">*</span></label>
                                <input type="email" class="board-form-control" id="custom_field_hr_email" name="custom_field_hr_email" value="{{ old('custom_field_hr_email', '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 경영지원 재무팀 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[경영지원 재무팀]</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_finance_manager" class="board-form-label">담당자 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_finance_manager" name="custom_field_finance_manager" value="{{ old('custom_field_finance_manager', '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_finance_phone" class="board-form-label">전화 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_finance_phone" name="custom_field_finance_phone" value="{{ old('custom_field_finance_phone', '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_finance_email" class="board-form-label">E-MAIL <span class="required">*</span></label>
                                <input type="email" class="board-form-control" id="custom_field_finance_email" name="custom_field_finance_email" value="{{ old('custom_field_finance_email', '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 영업 PDI사업 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[영업 PDI사업]</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_manager1" class="board-form-label">담당자 1 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_pdi_manager1" name="custom_field_pdi_manager1" value="{{ old('custom_field_pdi_manager1', '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_phone1" class="board-form-label">전화 1 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_pdi_phone1" name="custom_field_pdi_phone1" value="{{ old('custom_field_pdi_phone1', '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_manager2" class="board-form-label">담당자 2 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_pdi_manager2" name="custom_field_pdi_manager2" value="{{ old('custom_field_pdi_manager2', '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_phone2" class="board-form-label">전화 2 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_pdi_phone2" name="custom_field_pdi_phone2" value="{{ old('custom_field_pdi_phone2', '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_email" class="board-form-label">E-MAIL <span class="required">*</span></label>
                                <input type="email" class="board-form-control" id="custom_field_pdi_email" name="custom_field_pdi_email" value="{{ old('custom_field_pdi_email', '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 영업 하역/운송사업 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[영업 하역/운송사업]</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_logistics_manager" class="board-form-label">담당자 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_logistics_manager" name="custom_field_logistics_manager" value="{{ old('custom_field_logistics_manager', '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_logistics_phone" class="board-form-label">전화 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_logistics_phone" name="custom_field_logistics_phone" value="{{ old('custom_field_logistics_phone', '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_logistics_email" class="board-form-label">E-MAIL <span class="required">*</span></label>
                                <input type="email" class="board-form-control" id="custom_field_logistics_email" name="custom_field_logistics_email" value="{{ old('custom_field_logistics_email', '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 영업 특장차 제조사업 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[영업 특장차 제조사업]</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_vehicle_manager" class="board-form-label">담당자 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_vehicle_manager" name="custom_field_vehicle_manager" value="{{ old('custom_field_vehicle_manager', '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_vehicle_phone" class="board-form-label">전화 <span class="required">*</span></label>
                                <input type="text" class="board-form-control" id="custom_field_vehicle_phone" name="custom_field_vehicle_phone" value="{{ old('custom_field_vehicle_phone', '') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_vehicle_email" class="board-form-label">E-MAIL <span class="required">*</span></label>
                                <input type="email" class="board-form-control" id="custom_field_vehicle_email" name="custom_field_vehicle_email" value="{{ old('custom_field_vehicle_email', '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 저장
                    </button>
                    <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'notice') }}" class="btn btn-secondary">취소</a>
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
    <script src="{{ asset('js/backoffice/board-post-form.js') }}"></script>
@endsection