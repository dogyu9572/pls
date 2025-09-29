@extends('backoffice.layouts.app')

@section('title', '새 회원 추가')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/users.css') }}">
@endsection

@section('content')
<div class="user-form-container">
    <div class="form-header">      
        <a href="{{ route('backoffice.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="user-card">
                <div class="user-card-header">
                    <h6>기본 정보</h6>
                </div>
                <div class="user-card-body">
                    <form id="userForm" action="{{ route('backoffice.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">이름 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">이메일 <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="login_id">아이디</label>
                                <input type="text" class="form-control" id="login_id" name="login_id" value="{{ old('login_id') }}">
                            </div>

                            <div class="form-group">
                                <label for="password">비밀번호 <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">비밀번호 확인 <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="form-group">
                                <label for="department">부서</label>
                                <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
                            </div>

                            <div class="form-group">
                                <label for="position">직위</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}">
                            </div>

                            <div class="form-group">
                                <label for="contact">연락처</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}">
                            </div>

                            <div class="form-group">
                                <label for="is_active">계정 상태</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        활성화
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> 저장
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
