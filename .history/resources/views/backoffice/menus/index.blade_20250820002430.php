@extends('backoffice.layouts.app')

@section('title', '메뉴 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/menus.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection

@section('content')
<!-- 알림 모달 -->
<div id="alertModal" class="modal">
    <div class="modal-content">
        <div id="modalHeader" class="modal-header">
            <span id="modalTitle">알림</span>
            <span class="close-modal">&times;</span>
        </div>
        <div id="modalBody" class="modal-body">
            <p id="modalMessage"></p>
        </div>
    </div>
</div>

<!-- 삭제 확인 모달 -->
<div id="confirmDeleteModal" class="modal">
    <div class="modal-content">
        <div id="confirmModalHeader" class="modal-header warning">
            <span id="confirmModalTitle">삭제 확인</span>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <p id="confirmModalMessage">정말 이 메뉴를 삭제하시겠습니까?</p>
            <div class="modal-actions">
                <button id="confirmDeleteBtn" class="btn-confirm">삭제</button>
                <button id="cancelDeleteBtn" class="btn-cancel-modal">취소</button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success hidden-alert">
        {{ session('success') }}
    </div>
@endif

<div class="menu-container">
    <div class="menu-header">
        <div class="menu-actions">
            <a href="{{ route('backoffice.menus.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 새 메뉴 추가
            </a>
        </div>
    </div>

    <div class="menu-card">
        <div class="menu-card-header">
            <h6>메뉴 목록</h6>
        </div>
        <div class="menu-card-body">
            <div class="table-responsive">
                <table class="menu-table">
                    <thead>
                        <tr>
                            <th>이름</th>
                            <th>URL</th>
                            <th>상태</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr>
                            <td>
                                <div class="menu-name-container">
                                    <div class="drag-handle"><i class="fa fa-grip-vertical"></i></div>
                                    <span class="menu-name">{{ $menu->name }}</span>
                                </div>
                            </td>
                            <td>{{ $menu->url ?? '-' }}</td>
                            <td>
                                <span class="status-badge {{ $menu->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $menu->is_active ? '활성화' : '비활성화' }}
                                </span>
                            </td>
                            <td>
                                <div class="menu-btn-group">
                                    <a href="{{ route('backoffice.menus.edit', $menu) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> 수정
                                    </a>
                                    <form action="{{ route('backoffice.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 메뉴를 삭제하시겠습니까?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> 삭제
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @if($menu->children && $menu->children->count() > 0)
                            @foreach($menu->children as $child)
                            <tr class="submenu-row">
                                <td>
                                    <div class="menu-name-container submenu">
                                        <div class="drag-handle"><i class="fa fa-grip-vertical"></i></div>
                                        <span class="menu-name">└ {{ $child->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $child->url ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $child->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $child->is_active ? '활성화' : '비활성화' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="menu-btn-group">
                                        <a href="{{ route('backoffice.menus.edit', $child) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> 수정
                                        </a>
                                        <form action="{{ route('backoffice.menus.destroy', $child) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 메뉴를 삭제하시겠습니까?');">
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
                        @endif
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">등록된 메뉴가 없습니다.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="orderSavedMessage" class="order-saved-msg">
    <i class="fa fa-check-circle"></i> 메뉴 순서가 저장되었습니다.
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/menu-management.js') }}"></script>
@endpush
