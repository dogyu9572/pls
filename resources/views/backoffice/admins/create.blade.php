@extends('backoffice.layouts.app')

@section('title', '새 관리자 추가')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/admins.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/admin-permissions.js') }}"></script>
@endsection

@section('content')
<div class="admin-form-container">
    <div class="form-header">      
        <a href="{{ route('backoffice.admins.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h6>기본 정보</h6>
                </div>
                <div class="admin-card-body">
                    <form id="adminForm" action="{{ route('backoffice.admins.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-section">
            <h3>기본 정보</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="login_id">아이디</label>
                    <input type="text" id="login_id" name="login_id" value="{{ old('login_id') }}" placeholder="로그인 아이디를 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" id="password" name="password" required placeholder="비밀번호를 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">비밀번호 확인</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="비밀번호를 다시 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="name">성명</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="성명을 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="department">부서</label>
                    <input type="text" id="department" name="department" value="{{ old('department') }}" placeholder="부서를 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="position">직위</label>
                    <input type="text" id="position" name="position" value="{{ old('position') }}" placeholder="직위를 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="contact">연락처</label>
                    <input type="text" id="contact" name="contact" value="{{ old('contact') }}" placeholder="연락처를 입력하세요">
                </div>
                
                <div class="form-group">
                    <label for="email">이메일</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="이메일을 입력하세요">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>사용 여부</h3>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                    <span>사용</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                    <span>미사용</span>
                </label>
            </div>
        </div>

        <div class="form-section">
            <h3>권한 설정</h3>
            <div class="permissions-container">
                @php
                    $menus = \App\Models\AdminMenu::getPermissionMenuTree();
                @endphp
                
                @foreach($menus as $menu)
                    <div class="permission-category">
                        <div class="permission-category-header">
                            <h4>{{ $menu->name }}</h4>
                            <label class="permission-item parent-menu">
                                <input type="checkbox" name="permissions[{{ $menu->id }}]" value="1" {{ old('permissions.'.$menu->id) ? 'checked' : '' }}>
                                <span>{{ $menu->name }} 메뉴</span>
                            </label>
                        </div>
                        @if($menu->children->count() > 0)
                            <div class="permission-items">
                                @foreach($menu->children as $child)
                                    <label class="permission-item child-menu">
                                        <input type="checkbox" name="permissions[{{ $child->id }}]" value="1" {{ old('permissions.'.$child->id) ? 'checked' : '' }}>
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="permission-notice">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    슈퍼 관리자는 모든 메뉴에 자동으로 접근 권한이 부여됩니다.
                </small>
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
