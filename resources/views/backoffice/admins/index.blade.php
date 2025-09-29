@extends('backoffice.layouts.app')

@section('title', '관리자 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/admins.css') }}">
@endsection

@section('content')
<div class="board-container admins-page">
    <div class="board-header">
        <a href="{{ route('backoffice.admins.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> 새 관리자 추가
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="board-card">
        <div class="board-card-header">
            <div class="board-page-card-title">
                <h6>관리자 목록</h6>
            </div>
        </div>
        <div class="board-card-body">
            <!-- 검색 필터 -->
            <div class="admin-filter">
                <form method="GET" action="{{ route('backoffice.admins.index') }}" class="filter-form">
                    <!-- 첫 번째 줄 -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="name" class="filter-label">이름</label>
                            <input type="text" id="name" name="name" class="filter-input"
                                placeholder="이름을 입력하세요" value="{{ request('name') }}">
                        </div>
                        <div class="filter-group">
                            <label for="email" class="filter-label">이메일</label>
                            <input type="text" id="email" name="email" class="filter-input"
                                placeholder="이메일을 입력하세요" value="{{ request('email') }}">
                        </div>
                        <div class="filter-group">
                            <label for="role" class="filter-label">권한</label>
                            <select id="role" name="role" class="filter-select">
                                <option value="">전체</option>
                                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>슈퍼관리자</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>관리자</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="is_active" class="filter-label">상태</label>
                            <select id="is_active" name="is_active" class="filter-select">
                                <option value="">전체</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>활성화</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>비활성화</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- 두 번째 줄 -->
                    <div class="filter-row">
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
                                <a href="{{ route('backoffice.admins.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> 초기화
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if($admins->count() > 0)
                <!-- 목록 개수 선택 -->
                <div class="admin-list-header">
                    <div class="list-info">
                        <span class="list-count">Total : {{ $admins->total() }}</span>
                    </div>
                    <div class="list-controls">
                        <form method="GET" action="{{ route('backoffice.admins.index') }}" class="per-page-form">
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
                                <th>아이디</th>
                                <th>성명</th>
                                <th>부서</th>
                                <th>직위</th>
                                <th>이메일</th>
                                <th>연락처</th>
                                <th>권한</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $index => $admin)
                                <tr>
                                    <td>{{ $admins->total() - ($admins->currentPage() - 1) * $admins->perPage() - $loop->index }}</td>
                                    <td>{{ $admin->login_id ?: '-' }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->department ?: '-' }}</td>
                                    <td>{{ $admin->position ?: '-' }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->contact ?: '-' }}</td>
                                    <td>
                                        <span class="role-badge role-{{ $admin->role }}">
                                            @switch($admin->role)
                                                @case('super_admin')
                                                    슈퍼관리자
                                                    @break
                                                @case('admin')
                                                    관리자
                                                    @break
                                                @default
                                                    {{ $admin->role }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $admin->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $admin->is_active ? '활성화' : '비활성화' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="board-btn-group">
                                            <a href="{{ route('backoffice.admins.show', $admin) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> 보기
                                            </a>
                                            <a href="{{ route('backoffice.admins.edit', $admin) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> 수정
                                            </a>
                                            @if($admin->role !== 'super_admin')
                                                <form action="{{ route('backoffice.admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('이 관리자를 삭제하시겠습니까?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> 삭제
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <x-pagination :paginator="$admins" />
            @else
                <div class="no-data">
                    <p>등록된 관리자가 없습니다.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
