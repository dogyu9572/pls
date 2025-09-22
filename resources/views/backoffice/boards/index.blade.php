@extends('backoffice.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('title', $pageTitle ?? '')

@section('content')


@if(session('success'))
    <div class="alert alert-success hidden-alert">
        {{ session('success') }}
    </div>
@endif

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.boards.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> 새 게시판
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <div class="board-page-card-title">
                <h6>{{ $pageTitle }}</h6>                
            </div>
        </div>
        <div class="board-card-body">
            <!-- 검색 필터 -->
            <div class="board-filter">
                <form method="GET" action="{{ route('backoffice.boards.index') }}" class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="name" class="filter-label">게시판명</label>
                            <input type="text" id="name" name="name" class="filter-input"
                                placeholder="게시판명을 입력하세요" value="{{ request('name') }}">
                        </div>
                        <div class="filter-group">
                            <label for="is_active" class="filter-label">상태</label>
                            <select id="is_active" name="is_active" class="filter-select">
                                <option value="">전체</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>활성화</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>비활성화</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="skin_id" class="filter-label">스킨</label>
                            <select id="skin_id" name="skin_id" class="filter-select">
                                <option value="">전체</option>
                                @foreach(\App\Models\BoardSkin::where('is_active', true)->get() as $skin)
                                    <option value="{{ $skin->id }}" {{ request('skin_id') == $skin->id ? 'selected' : '' }}>
                                        {{ $skin->name }}
                                    </option>
                                @endforeach
                            </select>
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
                            <div class="filter-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> 검색
                                </button>
                                <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 목록 개수 선택 -->
            <div class="board-list-header">
                <div class="list-info">
                    <span class="list-count">Total : {{ $boards->total() }}</span>
                </div>
                <div class="list-controls">
                    <form method="GET" action="{{ route('backoffice.boards.index') }}" class="per-page-form">
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

            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>이름</th>
                            <th class="w15">게시판명</th>
                            <th class="w15">스킨</th>
                            <th class="w15">상태</th>
                            <th class="w15">생성일</th>
                            <th class="w15">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boards as $board)
                        <tr>
                            <td>{{ $boards->total() - ($boards->currentPage() - 1) * $boards->perPage() - $loop->index }}</td>
                            <td>{{ $board->name }}</td>
                            <td>{{ $board->slug }}</td>
                            <td>{{ $board->skin ? $board->skin->name : '없음' }}</td>
                            <td>
                                <span class="status-badge {{ $board->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $board->is_active ? '활성화' : '비활성화' }}
                                </span>
                            </td>
                            <td>{{ $board->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="board-btn-group">

                                    <a href="{{ route('backoffice.boards.edit', $board) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> 수정
                                    </a>
                                    <form action="{{ route('backoffice.boards.destroy', $board) }}" method="POST" class="d-inline" onsubmit="return confirm('이 게시판을 삭제하시겠습니까? 관련된 모든 데이터가 함께 삭제됩니다.');">
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
                            <td colspan="7" class="text-center">등록된 게시판이 없습니다.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :paginator="$boards" />
        </div>
    </div>
</div>
@endsection
