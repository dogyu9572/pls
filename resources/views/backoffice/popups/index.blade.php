@extends('backoffice.layouts.app')

@section('title', '팝업 관리')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/backoffice/popups.css') }}">
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
                <a href="{{ route('backoffice.popups.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> 새 팝업 추가
                </a>              
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <div class="board-page-card-title">
                    <h6>팝업 관리</h6>               
                </div>
            </div>
            <div class="board-card-body">
                <!-- 검색 필터 -->
                <div class="popup-filter">
                    <form method="GET" action="{{ route('backoffice.popups.index') }}" class="filter-form">
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
                                <label for="popup_type" class="filter-label">팝업타입</label>
                                <select id="popup_type" name="popup_type" class="filter-select">
                                    <option value="">전체</option>
                                    <option value="image" {{ request('popup_type') == 'image' ? 'selected' : '' }}>이미지</option>
                                    <option value="html" {{ request('popup_type') == 'html' ? 'selected' : '' }}>HTML</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="popup_display_type" class="filter-label">표시타입</label>
                                <select id="popup_display_type" name="popup_display_type" class="filter-select">
                                    <option value="">전체</option>
                                    <option value="normal" {{ request('popup_display_type') == 'normal' ? 'selected' : '' }}>일반팝업</option>
                                    <option value="layer" {{ request('popup_display_type') == 'layer' ? 'selected' : '' }}>레이어팝업</option>
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
                                <label for="title" class="filter-label">팝업제목</label>
                                <input type="text" id="title" name="title" class="filter-input"
                                    placeholder="팝업제목을 입력하세요" value="{{ request('title') }}">
                            </div>
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> 검색
                                    </button>
                                    <a href="{{ route('backoffice.popups.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> 초기화
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @if($popups->count() > 0)
                    <!-- 목록 개수 선택 -->
                    <div class="popup-list-header">
                        <div class="list-info">
                            <span class="list-count">총 {{ $popups->total() }}개</span>
                        </div>
                        <div class="list-controls">
                            <form method="GET" action="{{ route('backoffice.popups.index') }}" class="per-page-form">
                                @foreach(request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label for="per_page" class="per-page-label">목록개수:</label>
                                <select id="per_page" name="per_page" class="per-page-select" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="popup-list" id="popupList">
                        @foreach($popups as $popup)
                            <div class="popup-item" data-id="{{ $popup->id }}">
                                <div class="popup-drag-handle">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                                <div class="popup-info">
                                    <div class="popup-preview">
                                        @if($popup->popup_type === 'image' && $popup->popup_image)
                                            <img src="{{ asset('storage/' . $popup->popup_image) }}" alt="{{ $popup->title }}" class="popup-image">
                                        @elseif($popup->popup_type === 'html')
                                            <div class="html-preview">
                                                <i class="fas fa-code"></i>
                                                <span>HTML 팝업</span>
                                            </div>
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                                <span>이미지 없음</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="popup-details">
                                        <h6 class="popup-title">{{ $popup->title }}</h6>
                                        <div class="popup-meta">
                                            <span class="popup-type">{{ $popup->popup_type === 'image' ? '이미지' : 'HTML' }}</span>
                                            <span class="popup-display-type">{{ $popup->popup_display_type === 'normal' ? '일반팝업' : '레이어팝업' }}</span>
                                            <span class="popup-size">{{ $popup->width }}x{{ $popup->height }}px</span>
                                            <span class="popup-position">위치: {{ $popup->position_top }}, {{ $popup->position_left }}</span>
                                        </div>
                                        <div class="popup-meta">
                                            <span class="popup-status {{ $popup->is_active ? 'active' : 'inactive' }}">
                                                {{ $popup->is_active ? '사용' : '숨김' }}
                                            </span>
                                            <span class="popup-order">순서: {{ $popup->sort_order }}</span>
                                            @if($popup->use_period && $popup->start_date && $popup->end_date)
                                                <span class="popup-period">
                                                    게시기간: {{ $popup->start_date->format('Y-m-d') }} ~ {{ $popup->end_date->format('Y-m-d') }}
                                                </span>
                                            @else
                                                <span class="popup-period">게시기간: 상시 표출</span>
                                            @endif
                                            <span class="popup-created">등록일: {{ $popup->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="popup-actions">
                                    <div class="board-btn-group">
                                        <a href="{{ route('backoffice.popups.edit', $popup) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.popups.destroy', $popup) }}" method="POST" class="d-inline" onsubmit="return confirm('정말로 삭제하시겠습니까?')">
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
                    <x-pagination :paginator="$popups" />
                @else
                    <div class="empty-state">
                        <i class="fas fa-window-restore"></i>
                        <h5>등록된 팝업이 없습니다</h5>
                        <p>새 팝업을 추가해보세요.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/backoffice/popups.js') }}"></script>
@endsection
