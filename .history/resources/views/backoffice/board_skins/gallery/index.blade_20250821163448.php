@extends('backoffice.layouts.app')

@section('title', $board->name ?? '게시판')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice/gallery.css') }}">
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
                <button type="button" id="bulk-delete-btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> 선택 삭제
                </button>
                <a href="{{ route('backoffice.board-posts.create', $board->slug ?? 'gallery') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> 새 갤러리
                </a>              
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <div class="board-page-card-title">
                    <h6>{{ $board->name ?? '갤러리' }}</h6>
                    <span class="board-page-count">총 {{ $posts->total() }}개</span>
                </div>
            </div>
            <div class="board-card-body">
                <!-- 검색 필터 -->
                <div class="board-filter">
                    <form method="GET" action="{{ route('backoffice.board-posts.index', $board->slug ?? 'gallery') }}" class="filter-form">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label for="start_date" class="filter-label">등록일 시작</label>
                                <input type="date" id="start_date" name="start_date" class="filter-input"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="filter-group">
                                <label for="end_date" class="filter-label">등록일 끝</label>
                                <input type="date" id="end_date" name="end_date" class="filter-input"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="filter-group">
                                <label for="search_type" class="filter-label">검색 구분</label>
                                <select id="search_type" name="search_type" class="filter-select">
                                    <option value="">전체</option>
                                    <option value="title" {{ request('search_type') == 'title' ? 'selected' : '' }}>제목</option>
                                    <option value="content" {{ request('search_type') == 'content' ? 'selected' : '' }}>내용</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="keyword" class="filter-label">검색어</label>
                                <input type="text" id="keyword" name="keyword" class="filter-input"
                                    placeholder="검색어를 입력하세요" value="{{ request('keyword') }}">
                            </div>
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> 검색
                                    </button>
                                    <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'gallery') }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> 초기화
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 갤러리 그리드 -->
                <div class="gallery-grid">
                    @forelse($posts as $post)
                        <div class="gallery-item">
                            @php
                                // 썸나일 이미지 결정 로직
                                $thumbnailSrc = null;
                                $thumbnailAlt = $post->image_alt ?? $post->title;
                                
                                if ($post->thumbnail) {
                                    // 1순위: 전용 썸나일 이미지
                                    $thumbnailSrc = asset('storage/' . $post->thumbnail);
                                } elseif ($post->images) {
                                    // 2순위: 갤러리 이미지 중 첫 번째
                                    $images = json_decode($post->images, true);
                                    if (is_array($images) && count($images) > 0) {
                                        $firstImage = $images[0];
                                        $thumbnailSrc = asset('storage/' . $firstImage['path']);
                                        $thumbnailAlt = $firstImage['name'] ?? $post->title;
                                    }
                                }
                            @endphp
                            
                            @if($thumbnailSrc)
                                <img src="{{ $thumbnailSrc }}" 
                                     alt="{{ $thumbnailAlt }}" 
                                     class="gallery-thumbnail"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="gallery-thumbnail d-flex align-items-center justify-content-center" style="display: none;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @else
                                <div class="gallery-thumbnail d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="gallery-content">
                                <div class="gallery-title">
                                    @if ($post->is_notice)
                                        <span class="board-notice-badge">공지</span>
                                    @endif
                                    {{ $post->title }}
                                </div>
                                
                                <div class="gallery-meta">
                                    <div>작성자: {{ $post->author_name ?? '알 수 없음' }}</div>
                                    <div>등록일: {{ $post->created_at->format('Y-m-d') }}</div>
                                    <div>조회수: {{ $post->view_count ?? 0 }}</div>
                                    @if($post->images)
                                        @php
                                            $imageCount = count(json_decode($post->images, true) ?? []);
                                        @endphp
                                        <div>이미지: {{ $imageCount }}장</div>
                                    @endif
                                </div>
                                
                                <div class="gallery-actions">
                                    <a href="{{ route('backoffice.board-posts.show', [$board->slug ?? 'gallery', $post->id]) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> 보기
                                    </a>
                                    <a href="{{ route('backoffice.board-posts.edit', [$board->slug ?? 'gallery', $post->id]) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> 수정
                                    </a>
                                    <form
                                        action="{{ route('backoffice.board-posts.destroy', [$board->slug ?? 'gallery', $post->id]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('정말 이 갤러리를 삭제하시겠습니까?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> 삭제
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <p class="text-muted">등록된 갤러리가 없습니다.</p>
                        </div>
                    @endforelse
                </div>

                <x-pagination :paginator="$posts" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endpush
