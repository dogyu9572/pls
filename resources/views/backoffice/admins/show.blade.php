@extends('backoffice.layouts.app')

@section('title', '관리자 정보')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/admins.css') }}">
@endsection

@section('content')
<div class="admin-detail-container">
    <div class="detail-header">
        <h1>관리자 정보</h1>
        <div class="detail-actions">
            <a href="{{ route('backoffice.admins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h6>기본 정보</h6>
                </div>
                <div class="admin-card-body">
                    <div class="detail-grid">
                <div class="detail-item">
                    <label>아이디</label>
                    <span>{{ $admin->login_id ?: '-' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>성명</label>
                    <span>{{ $admin->name }}</span>
                </div>
                
                <div class="detail-item">
                    <label>부서</label>
                    <span>{{ $admin->department ?: '-' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>직위</label>
                    <span>{{ $admin->position ?: '-' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>이메일</label>
                    <span>{{ $admin->email }}</span>
                </div>
                
                <div class="detail-item">
                    <label>연락처</label>
                    <span>{{ $admin->contact ?: '-' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>권한</label>
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
                </div>
                
                <div class="detail-item">
                    <label>상태</label>
                    <span class="status-badge {{ $admin->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $admin->is_active ? '활성화' : '비활성화' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <label>마지막 로그인</label>
                    <span>{{ $admin->last_login_at ? $admin->last_login_at->format('Y-m-d H:i:s') : '-' }}</span>
                </div>
                
                <div class="detail-item">
                    <label>가입일</label>
                    <span>{{ $admin->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                
                <div class="detail-item">
                    <label>수정일</label>
                    <span>{{ $admin->updated_at->format('Y-m-d H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-card-header">
            <h6>권한 설정</h6>
        </div>
        <div class="detail-card-body">
            @if($admin->isSuperAdmin())
                <div class="super-admin-notice">
                    <div class="alert alert-warning">
                        <i class="fas fa-crown"></i>
                        <strong>슈퍼 관리자</strong><br>
                        슈퍼 관리자는 모든 메뉴에 자동으로 접근 권한이 부여됩니다.
                    </div>
                </div>
            @else
                <div class="granted-permissions-container">
                    @php
                        $menus = \App\Models\AdminMenu::getPermissionMenuTree();
                        $userPermissions = $admin->getAllMenuPermissions();
                    @endphp
                    
                    @foreach($menus as $menu)
                        @php
                            $hasParentPermission = $userPermissions[$menu->id] ?? false;
                            $hasChildPermissions = false;
                            if ($menu->children->count() > 0) {
                                foreach ($menu->children as $child) {
                                    if ($userPermissions[$child->id] ?? false) {
                                        $hasChildPermissions = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        
                        @if($hasParentPermission || $hasChildPermissions)
                            <div class="permission-category">
                                <div class="permission-category-header">
                                    <h4>{{ $menu->name }}</h4>
                                </div>
                                <div class="permission-items">
                                    @if($hasParentPermission)
                                        <div class="permission-item granted">
                                            <span class="permission-icon">
                                                <i class="fas fa-check-circle text-success"></i>
                                            </span>
                                            <span>{{ $menu->name }} 메뉴</span>
                                        </div>
                                    @endif
                                    
                                    @if($menu->children->count() > 0)
                                        @foreach($menu->children as $child)
                                            @if($userPermissions[$child->id] ?? false)
                                                <div class="permission-item child-menu granted">
                                                    <span class="permission-icon">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    </span>
                                                    <span>{{ $child->name }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
                    @php
                        $hasAnyPermissions = false;
                        foreach ($menus as $menu) {
                            if ($userPermissions[$menu->id] ?? false) {
                                $hasAnyPermissions = true;
                                break;
                            }
                            if ($menu->children->count() > 0) {
                                foreach ($menu->children as $child) {
                                    if ($userPermissions[$child->id] ?? false) {
                                        $hasAnyPermissions = true;
                                        break 2;
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    @if(!$hasAnyPermissions)
                        <div class="no-permissions">
                            <div class="alert alert-secondary">
                                <i class="fas fa-info-circle"></i>
                                <strong>권한 없음</strong><br>
                                이 관리자에게는 특별한 메뉴 접근 권한이 부여되지 않았습니다.
                            </div>
                        </div>
                    @endif
                </div>
            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
