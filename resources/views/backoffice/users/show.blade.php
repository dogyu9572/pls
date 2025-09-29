@extends('backoffice.layouts.app')

@section('title', '회원 정보')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/users.css') }}">
@endsection

@section('content')
<div class="user-detail-container">
    <div class="detail-header">
        <h1>회원 정보</h1>
        <div class="detail-actions">
            <a href="{{ route('backoffice.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="user-card">
                <div class="user-card-header">
                    <h6>기본 정보</h6>
                </div>
                <div class="user-card-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>이름</label>
                            <span>{{ $user->name }}</span>
                        </div>
                        <div class="detail-item">
                            <label>이메일</label>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div class="detail-item">
                            <label>아이디</label>
                            <span>{{ $user->login_id ?: '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <label>부서</label>
                            <span>{{ $user->department ?: '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <label>직위</label>
                            <span>{{ $user->position ?: '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <label>연락처</label>
                            <span>{{ $user->contact ?: '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <label>가입일</label>
                            <span>{{ $user->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>마지막 로그인</label>
                            <span>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <label>상태</label>
                            <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $user->is_active ? '활성화' : '비활성화' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
