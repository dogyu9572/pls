@extends('backoffice.layouts.app')

@section('title', $board->name ?? '게시판')

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
            <h6>사업문의 관리</h6>
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

            <form action="{{ route('backoffice.board-posts.update', [$board->slug ?? 'notice', $post->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- 기본 필드들 히든 처리 -->
                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="title" value="사업문의">
                </div>
                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="category" value="{{ $post->category ?? '일반' }}">
                </div>
                <div class="board-form-group" style="display: none;">
                    <input type="hidden" name="content" value="{{ $post->content ?? '내용' }}">
                </div>

                @php
                    $customFields = $post->custom_fields ? json_decode($post->custom_fields, true) : [];
                @endphp

                <!-- PDI 사업문의 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[PDI 사업문의]</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_tel" class="board-form-label">TEL <span class="required">*</span></label>
                                <input type="text" 
                                       class="board-form-control" 
                                       id="custom_field_pdi_tel" 
                                       name="custom_field_pdi_tel" 
                                       value="{{ old('custom_field_pdi_tel', $customFields['pdi_tel'] ?? '') }}"
                                       placeholder="전화번호를 입력하세요"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_pdi_mail" class="board-form-label">MAIL <span class="required">*</span></label>
                                <input type="email" 
                                       class="board-form-control" 
                                       id="custom_field_pdi_mail" 
                                       name="custom_field_pdi_mail" 
                                       value="{{ old('custom_field_pdi_mail', $customFields['pdi_mail'] ?? '') }}"
                                       placeholder="이메일을 입력하세요"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 항만물류 사업문의 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[항만물류 사업문의]</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_logistics_tel" class="board-form-label">TEL <span class="required">*</span></label>
                                <input type="text" 
                                       class="board-form-control" 
                                       id="custom_field_logistics_tel" 
                                       name="custom_field_logistics_tel" 
                                       value="{{ old('custom_field_logistics_tel', $customFields['logistics_tel'] ?? '') }}"
                                       placeholder="전화번호를 입력하세요"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_logistics_mail" class="board-form-label">MAIL <span class="required">*</span></label>
                                <input type="email" 
                                       class="board-form-control" 
                                       id="custom_field_logistics_mail" 
                                       name="custom_field_logistics_mail" 
                                       value="{{ old('custom_field_logistics_mail', $customFields['logistics_mail'] ?? '') }}"
                                       placeholder="이메일을 입력하세요"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 특장차 사업문의 섹션 -->
                <div class="business-inquiry-section">
                    <h6 class="section-title">[특장차 사업문의]</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_special_vehicle_tel" class="board-form-label">TEL <span class="required">*</span></label>
                                <input type="text" 
                                       class="board-form-control" 
                                       id="custom_field_special_vehicle_tel" 
                                       name="custom_field_special_vehicle_tel" 
                                       value="{{ old('custom_field_special_vehicle_tel', $customFields['special_vehicle_tel'] ?? '') }}"
                                       placeholder="전화번호를 입력하세요"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="board-form-group">
                                <label for="custom_field_special_vehicle_mail" class="board-form-label">MAIL <span class="required">*</span></label>
                                <input type="email" 
                                       class="board-form-control" 
                                       id="custom_field_special_vehicle_mail" 
                                       name="custom_field_special_vehicle_mail" 
                                       value="{{ old('custom_field_special_vehicle_mail', $customFields['special_vehicle_mail'] ?? '') }}"
                                       placeholder="이메일을 입력하세요"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 브로셔 다운로드 섹션 -->
                    <div class="brochure-section">
                        <h6 class="brochure-title">브로셔 다운로드</h6>
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
                                
                                @if($post->attachments)
                                    @php
                                        $existingAttachments = json_decode($post->attachments, true);
                                    @endphp
                                    @if($existingAttachments && is_array($existingAttachments) && count($existingAttachments) > 0)
                                        <div class="board-existing-files">
                                            <div class="board-attachment-list">
                                                @foreach($existingAttachments as $index => $attachment)
                                                    <div class="board-attachment-item existing-file" data-index="{{ $index }}">
                                                        <i class="fas fa-file"></i>
                                                        <span class="board-attachment-name">{{ $attachment['name'] }}</span>
                                                        <span class="board-attachment-size">({{ number_format($attachment['size'] / 1024 / 1024, 2) }}MB)</span>
                                                        <button type="button" class="board-attachment-remove" onclick="removeExistingFile({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="existing_attachments[]" value="{{ json_encode($attachment) }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                
                                <div class="board-file-preview" id="filePreview"></div>
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
    
    <script>
        // 기존 첨부파일 제거 기능
        function removeExistingFile(index) {
            const fileItem = document.querySelector(`[data-index="${index}"]`);
            if (fileItem) {
                fileItem.remove();
            }
        }
    </script>
@endsection