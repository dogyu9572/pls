@extends('backoffice.layouts.app')

@section('title', '배너 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/banners.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/banners.js') }}"></script>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success board-hidden-alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger board-hidden-alert">
        {{ session('error') }}
    </div>
@endif

<div class="board-container">
    <div class="board-page-header">
        <div class="board-page-buttons">
            <a href="{{ route('backoffice.banners.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 새 배너 추가
            </a>              
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <div class="board-page-card-title">
                <h6>배너 관리</h6>               
            </div>
        </div>
        <div class="board-card-body">
            <!-- 검색 필터 -->
            <div class="banner-filter">
                <form method="GET" action="{{ route('backoffice.banners.index') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="is_active" class="filter-label">사용여부</label>
                            <select id="is_active" name="is_active" class="filter-select">
                                <option value="">전체</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>사용</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="start_date" class="filter-label">게시기간</label>
                            <div class="date-range">
                                <input type="date" id="start_date" name="start_date" class="filter-input"
                                    value="{{ request('start_date') }}">
                                <span class="date-separator">~</span>
                                <input type="date" id="end_date" name="end_date" class="filter-input"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label for="created_from" class="filter-label">등록일</label>
                            <div class="date-range">
                                <input type="date" id="created_from" name="created_from" class="filter-input"
                                    value="{{ request('created_from') }}">
                                <span class="date-separator">~</span>
                                <input type="date" id="created_to" name="created_to" class="filter-input"
                                    value="{{ request('created_to') }}">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label for="title" class="filter-label">배너제목</label>
                            <input type="text" id="title" name="title" class="filter-input"
                                placeholder="배너제목을 입력하세요" value="{{ request('title') }}">
                        </div>
                        <div class="filter-group">
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.banners.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if($banners->count() > 0)
                <!-- 목록 개수 선택 -->
                <div class="banner-list-header">
                    <div class="list-info">
                        <span class="list-count">Total : {{ $banners->total() }}</span>
                    </div>
                    <div class="list-controls">
                        <form method="GET" action="{{ route('backoffice.banners.index') }}" class="per-page-form">
                            @foreach(request()->except('per_page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <label for="per_page" class="per-page-label">목록 개수:</label>
                            <select id="per_page" name="per_page" class="per-page-select" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="banner-list" id="bannerList">
                    @foreach($banners as $banner)
                        <div class="banner-item" data-id="{{ $banner->id }}">
                            <div class="banner-drag-handle">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <div class="banner-info">
                                <div class="banner-preview">
                                    @if($banner->banner_type === 'video')
                                        @if($banner->video_file)
                                            <video class="banner-video-thumbnail" muted>
                                                <source src="{{ asset('storage/' . $banner->video_file) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-video"></i>
                                                <span>영상 없음</span>
                                            </div>
                                        @endif
                                        <div class="video-overlay">
                                            <i class="fas fa-play"></i>
                                            @if($banner->video_duration)
                                                <span class="video-duration">{{ $banner->video_duration }}초</span>
                                            @endif
                                        </div>
                                    @else
                                        @if($banner->desktop_image)
                                            <img src="{{ asset('storage/' . $banner->desktop_image) }}" alt="{{ $banner->title }}" class="banner-image">
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                                <span>이미지 없음</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="banner-details">
                                    <h6 class="banner-title">{{ $banner->title }}</h6>                                   
                                    <div class="banner-meta">
                                        <span class="banner-status {{ $banner->is_active ? 'active' : 'inactive' }}">
                                            {{ $banner->is_active ? '사용' : '숨김' }}
                                        </span>
                                        <span class="banner-type {{ $banner->banner_type === 'video' ? 'video' : 'image' }}">
                                            {{ $banner->banner_type === 'video' ? '영상' : '이미지' }}
                                        </span>
                                        <span class="banner-language">{{ $banner->language === 'ko' ? '국문' : '영문' }}</span>
                                        <span class="banner-order">순서: {{ $banner->sort_order }}</span>
                                        @if($banner->use_period && $banner->start_date && $banner->end_date)
                                            <span class="banner-period">
                                                게시기간: {{ $banner->start_date->format('Y-m-d') }} ~ {{ $banner->end_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="banner-period">게시기간: 상시 표출</span>
                                        @endif
                                        <span class="banner-created">등록일: {{ $banner->created_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="banner-actions">
                                <div class="board-btn-group">
                                    <a href="{{ route('backoffice.banners.edit', $banner) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> 수정
                                    </a>
                                    <form action="{{ route('backoffice.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('정말로 삭제하시겠습니까?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> 삭제
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-pagination :paginator="$banners" />
            @else
                <div class="empty-state">
                    <i class="fas fa-image"></i>
                    <h5>등록된 배너가 없습니다</h5>
                    <p>새 배너를 추가해보세요.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
