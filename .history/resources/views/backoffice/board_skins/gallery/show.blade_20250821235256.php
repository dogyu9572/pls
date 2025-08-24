@extends('backoffice.layouts.app')

@section('title', $board->name ?? '갤러리')

@section('styles')
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
                <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'gallery') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 목록으로
                </a>
            </div>
        </div>

        <div class="gallery-showcase">
            @php
                // 메인 이미지 결정 로직
                $mainImageSrc = null;
                $mainImageAlt = $post->image_alt ?? $post->title;
                
                if ($post->thumbnail) {
                    // 1순위: 전용 썸나일 이미지
                    $mainImageSrc = asset('storage/' . $post->thumbnail);
                } elseif ($post->images) {
                    // 2순위: 갤러리 이미지 중 첫 번째
                    $images = json_decode($post->images, true);
                    if (is_array($images) && count($images) > 0) {
                        $firstImage = $images[0];
                        $mainImageSrc = asset('storage/' . $firstImage['path']);
                        $mainImageAlt = $firstImage['name'] ?? $post->title;
                    }
                }
            @endphp
            
            @if($mainImageSrc)
                <img src="{{ $mainImageSrc }}" 
                     alt="{{ $mainImageAlt }}" 
                     class="gallery-main-image" 
                     id="mainImage"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="gallery-main-image d-flex align-items-center justify-content-center bg-light" style="display: none;">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @else
                <div class="gallery-main-image d-flex align-items-center justify-content-center bg-light">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @endif


        </div>

        <div class="gallery-content">
            <div class="gallery-title">
                @if ($post->is_notice)
                    <span class="board-notice-badge">공지</span>
                @endif
                {{ $post->title }}
            </div>

            <div class="gallery-meta">
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>작성자: {{ $post->author_name ?? '알 수 없음' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>등록일: {{ $post->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>조회수: {{ $post->view_count ?? 0 }}</span>
                </div>
                @if($post->category)
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span>카테고리: {{ $post->category }}</span>
                    </div>
                @endif

            </div>

            @if($post->content)
                <div class="gallery-description">
                    {!! $post->content !!}
                </div>
            @endif

            <!-- 커스텀 필드 정보 표시 -->
            @if($board->custom_fields_config && $post->custom_fields)
                @php
                    $customFields = json_decode($post->custom_fields, true);
                @endphp
                @if($customFields && is_array($customFields))
                    <div class="board-post-custom-fields">
                        <h6>추가 정보</h6>
                        <div class="board-custom-fields-list">
                            @foreach($board->custom_fields_config as $fieldConfig)
                                @if(isset($customFields[$fieldConfig['name']]) && !empty($customFields[$fieldConfig['name']]))
                                    <div class="board-custom-field-item">
                                        <span class="board-custom-field-label">{{ $fieldConfig['label'] }}:</span>
                                        <span class="board-custom-field-value">
                                            @if($fieldConfig['type'] === 'date')
                                                {{ \Carbon\Carbon::parse($customFields[$fieldConfig['name']])->format('Y-m-d') }}
                                            @elseif($fieldConfig['type'] === 'checkbox')
                                                {{ $customFields[$fieldConfig['name']] == '1' ? '예' : '아니오' }}
                                            @elseif($fieldConfig['type'] === 'radio')
                                                {{ $customFields[$fieldConfig['name']] }}
                                            @else
                                                {{ $customFields[$fieldConfig['name']] }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <div class="gallery-actions">
                <a href="{{ route('backoffice.board-posts.edit', [$board->slug ?? 'gallery', $post->id]) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> 수정
                </a>
                <form action="{{ route('backoffice.board-posts.destroy', [$board->slug ?? 'gallery', $post->id]) }}" 
                      method="POST" class="d-inline" 
                      onsubmit="return confirm('정말 이 갤러리를 삭제하시겠습니까?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> 삭제
                    </button>
                </form>
                <button type="button" class="btn btn-info" onclick="openLightbox()">
                    <i class="fas fa-expand"></i> 전체화면
                </button>
            </div>
        </div>
    </div>

    <!-- 라이트박스 -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <div class="lightbox-close" onclick="closeLightbox()">&times;</div>
        <img id="lightboxImage" src="" alt="전체화면 이미지">
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endpush
