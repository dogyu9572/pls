@extends('backoffice.layouts.app')

@section('title', '공지사항')

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
                <button type="button" id="bulk-delete-btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> 선택 삭제
                </button>
                <a href="{{ route('backoffice.board-posts.create', 'notice') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> 새 게시글
                </a>              
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <div class="board-page-card-title">
                    <h6>공지사항</h6>
                    <span class="board-page-count">총 {{ $posts->total() }}개</span>
                </div>
            </div>
            <div class="board-card-body">
                <!-- 검색 필터 -->
                <div class="board-filter">
                    <form method="GET" action="{{ route('backoffice.board-posts.index', 'notice') }}" class="filter-form">
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
                                    <option value="title" {{ request('search_type') == 'title' ? 'selected' : '' }}>제목
                                    </option>
                                    <option value="content" {{ request('search_type') == 'content' ? 'selected' : '' }}>내용
                                    </option>
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
                                    <a href="{{ route('backoffice.board-posts.index', 'notice') }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> 초기화
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th class="w5 board-checkbox-column">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th class="w5">번호</th>
                                <th class="w10">구분</th>
                                <th>제목</th>
                                <th class="w10">작성자</th>
                                <th class="w10">조회수</th>
                                <th class="w10">작성일</th>
                                <th class="w15">관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_posts[]" value="{{ $post->id }}" class="form-check-input post-checkbox">
                                    </td>
                                    <td>
                                        @if ($post->is_notice)
                                            <span class="board-notice-badge">공지</span>
                                        @else
                                            {{ $posts->total() - ($posts->currentPage() - 1) * $posts->perPage() - $loop->index }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-general">일반</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('backoffice.board_posts.show', ['notice', $post->id]) }}"
                                            class="board-post-title-link">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td>{{ $post->author_name ?? '알 수 없음' }}</td>
                                    <td>{{ $post->view_count ?? 0 }}</td>
                                    <td>{{ $post->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="board-btn-group">
                                            <a href="{{ route('backoffice.board_posts.show', ['notice', $post->id]) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> 보기
                                            </a>
                                            <a href="{{ route('backoffice.board_posts.edit', ['notice', $post->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> 수정
                                            </a>
                                            <form
                                                action="{{ route('backoffice.board_posts.destroy', ['notice', $post->id]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> 삭제
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">등록된 게시글이 없습니다.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <x-pagination :paginator="$posts" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endpush
