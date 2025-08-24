@extends('backoffice.layouts.app')

@section('title', '회원 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/users.css') }}">
@endsection

@section('content')
<div class="user-container">
    <div class="user-header">
        <div class="user-actions">
            <a href="{{ route('backoffice.users.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 새 회원 추가
            </a>
        </div>
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

    <div class="user-card">
        <div class="user-card-header">
            <h6>회원 목록</h6>
        </div>
        <div class="user-card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>이름</th>
                                <th>이메일</th>
                                <th>가입일</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="status-badge status-active">활성</span>
                                        @else
                                            <span class="status-badge status-inactive">비활성</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="user-btn-group">
                                            <a href="{{ route('backoffice.users.show', $user) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> 보기
                                            </a>
                                            <a href="{{ route('backoffice.users.edit', $user) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> 수정
                                            </a>
                                            <form action="{{ route('backoffice.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('이 회원을 삭제하시겠습니까?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> 삭제
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $users->links() }}
                </div>
            @else
                <div class="no-data">
                    <p>등록된 회원이 없습니다.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
