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
            <h6>관리자 목록</h6>
        </div>
        <div class="board-card-body">
            @if($admins->count() > 0)
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
                                    <td>{{ $admins->count() - $loop->index }}</td>
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
            @else
                <div class="no-data">
                    <p>등록된 관리자가 없습니다.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
