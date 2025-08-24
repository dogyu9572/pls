@extends('backoffice.layouts.app')

@section('title', $board->name ?? '갤러리')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <style>
        .gallery-showcase {
            margin-bottom: 2rem;
        }
        
        .gallery-main-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 1rem;
        }
        
        .gallery-thumbnails {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .gallery-thumbnail {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 3px solid transparent;
        }
        
        .gallery-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .gallery-thumbnail.active {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .gallery-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .gallery-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            border-bottom: 3px solid #007bff;
            padding-bottom: 0.5rem;
        }
        
        .gallery-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .meta-item i {
            color: #007bff;
            width: 20px;
        }
        
        .gallery-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 2rem;
        }
        
        .gallery-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .gallery-actions .btn {
            min-width: 120px;
        }
        
        .custom-fields {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .custom-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .custom-field:last-child {
            border-bottom: none;
        }
        
        .custom-field-label {
            font-weight: 600;
            color: #495057;
        }
        
        .custom-field-value {
            color: #333;
        }
        
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: none;
            z-index: 9999;
            cursor: pointer;
        }
        
        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 8px;
        }
        
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
        }
    </style>
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
            @if($post->thumbnail)
                <img src="{{ asset('storage/' . $post->thumbnail) }}" 
                     alt="{{ $post->image_alt ?? $post->title }}" 
                     class="gallery-main-image" 
                     id="mainImage">
            @elseif($post->images)
                @php
                    $images = json_decode($post->images, true);
                    $firstImage = $images[0] ?? null;
                @endphp
                @if($firstImage)
                    <img src="{{ asset('storage/' . $firstImage['path']) }}" 
                         alt="{{ $firstImage['name'] ?? $post->title }}" 
                         class="gallery-main-image" 
                         id="mainImage">
                @endif
            @else
                <div class="gallery-main-image d-flex align-items-center justify-content-center bg-light">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @endif

            @if($post->images && count(json_decode($post->images, true)) > 1)
                <div class="gallery-thumbnails">
                    @foreach(json_decode($post->images, true) as $index => $image)
                        <img src="{{ asset('storage/' . $image['path']) }}" 
                             alt="{{ $image['name'] ?? '갤러리 이미지' }}" 
                             class="gallery-thumbnail {{ $index === 0 ? 'active' : '' }}"
                             onclick="changeMainImage(this, '{{ asset('storage/' . $image['path']) }}', {{ $index }})">
                    @endforeach
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
                @if($post->images)
                    @php
                        $imageCount = count(json_decode($post->images, true) ?? []);
                    @endphp
                    <div class="meta-item">
                        <i class="fas fa-images"></i>
                        <span>이미지: {{ $imageCount }}장</span>
                    </div>
                @endif
            </div>

            @if($post->content)
                <div class="gallery-description">
                    {!! $post->content !!}
                </div>
            @endif

            @if($post->custom_fields)
                <div class="custom-fields">
                    <h5 class="mb-3"><i class="fas fa-cogs"></i> 추가 정보</h5>
                    @foreach($post->custom_fields as $key => $value)
                        <div class="custom-field">
                            <span class="custom-field-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="custom-field-value">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
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
    <script>
        // 메인 이미지 변경
        function changeMainImage(thumbnail, imagePath, index) {
            // 메인 이미지 변경
            document.getElementById('mainImage').src = imagePath;
            
            // 썸나일 활성화 상태 변경
            document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }

        // 라이트박스 열기
        function openLightbox() {
            const mainImage = document.getElementById('mainImage');
            if (mainImage && mainImage.src) {
                document.getElementById('lightboxImage').src = mainImage.src;
                document.getElementById('lightbox').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        // 라이트박스 닫기
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // ESC 키로 라이트박스 닫기
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
@endpush
