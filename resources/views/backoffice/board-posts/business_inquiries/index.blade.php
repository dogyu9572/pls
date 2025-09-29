@extends('backoffice.layouts.app')

@section('title', $board->name ?? '게시판')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success board-hidden-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="board-container">
        <div class="board-page-header">
            <div class="board-page-buttons">
                @if($posts->count() > 0)
                    <a href="{{ route('backoffice.board-posts.edit', [$board->slug ?? 'notice', $posts->first()->id]) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> 수정
                    </a>
                @else
                    <a href="{{ route('backoffice.board-posts.create', $board->slug ?? 'notice') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> 새 게시글
                    </a>
                @endif
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <div class="board-page-card-title">
                    <h6>사업문의 관리</h6>
                </div>
            </div>
            <div class="board-card-body">
                @if($posts->count() > 0)
                    @php
                        $post = $posts->first();
                        $customFields = $post->custom_fields ? json_decode($post->custom_fields, true) : [];
                    @endphp
                    
                    <!-- PDI 사업문의 섹션 -->
                    <div class="business-inquiry-section">
                        <h5 class="section-title">[PDI 사업문의]</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">TEL</label>
                                    <div class="info-value">{{ $customFields['pdi_tel'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">MAIL</label>
                                    <div class="info-value">{{ $customFields['pdi_mail'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 항만물류 사업문의 섹션 -->
                    <div class="business-inquiry-section">
                        <h5 class="section-title">[항만물류 사업문의]</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">TEL</label>
                                    <div class="info-value">{{ $customFields['logistics_tel'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">MAIL</label>
                                    <div class="info-value">{{ $customFields['logistics_mail'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 특장차 사업문의 섹션 -->
                    <div class="business-inquiry-section">
                        <h5 class="section-title">[특장차 사업문의]</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">TEL</label>
                                    <div class="info-value">{{ $customFields['special_vehicle_tel'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">MAIL</label>
                                    <div class="info-value">{{ $customFields['special_vehicle_mail'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 브로셔 다운로드 섹션 -->
                        @if($post->attachments)
                            @php
                                $existingAttachments = json_decode($post->attachments, true);
                            @endphp
                            @if($existingAttachments && is_array($existingAttachments) && count($existingAttachments) > 0)
                            <div class="brochure-section">
                                <h6 class="brochure-title">브로셔 다운로드</h6>
                                @foreach($existingAttachments as $attachment)
                                <div class="brochure-file">
                                    <i class="fas fa-file text-primary"></i>
                                    <span class="brochure-filename">{{ $attachment['name'] }}</span>
                                    <span class="brochure-size">({{ number_format($attachment['size'] / 1024 / 1024, 2) }}MB)</span>
                                    <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> 다운로드
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        @endif
                    </div>

                    <div class="board-form-actions">
                        <a href="{{ route('backoffice.board-posts.edit', [$board->slug ?? 'notice', $post->id]) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> 수정
                        </a>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">등록된 사업문의 정보가 없습니다.</h5>
                        <p class="text-muted">새 게시글을 작성하여 사업문의 정보를 등록해주세요.</p>
                        <a href="{{ route('backoffice.board-posts.create', $board->slug ?? 'notice') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> 새 게시글 작성
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

<style>
.business-inquiry-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #007bff;
}

.brochure-section {
    margin-top: 1.5rem;
    padding: 1rem;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
}

.brochure-title {
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 1rem;
}

.brochure-file {
    display: flex;
    align-items: center;
    gap: 10px;
}

.brochure-filename {
    flex: 1;
    font-weight: 500;
}

.info-item {
    margin-bottom: 1rem;
}

.info-label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.info-value {
    padding: 0.5rem;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    min-height: 38px;
    display: flex;
    align-items: center;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.board-form-actions {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #dee2e6;
}
</style>
@endsection

@section('scripts')
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endsection