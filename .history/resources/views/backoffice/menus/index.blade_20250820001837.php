@extends('backoffice.layouts.app')

@section('title', '메뉴 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/menu-management.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection

@section('content')
<div id="orderSavedMessage" class="order-saved-msg">
    <i class="fa fa-check-circle"></i> 메뉴 순서가 저장되었습니다.
</div>

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

<div class="menu-header-container">
    <div></div> <!-- 왼쪽 여백 -->
    <a href="{{ route('backoffice.menus.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> 새 메뉴 추가
    </a>
</div>

<div class="menu-header">
    <div class="col-drag-handle"></div>
    <div class="col-name">이름</div>
    <div class="col-url">URL</div>
    <div class="col-status">상태</div>
    <div class="col-actions">관리</div>
</div>

<ul id="mainMenuList" class="menu-list">
    @forelse($menus as $menu)
        <li class="menu-item" data-id="{{ $menu->id }}">
            <div class="menu-info">
                <div class="drag-handle"><i class="fa fa-grip-vertical"></i></div>
                <span class="menu-name">{{ $menu->name }}</span>
                <span class="menu-url">{{ $menu->url ?? '-' }}</span>
                <span class="menu-status">
                    <span class="status-badge {{ $menu->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $menu->is_active ? '활성화' : '비활성화' }}
                    </span>
                </span>
            </div>
            <div class="menu-actions">
                <a href="{{ route('backoffice.menus.edit', $menu) }}" class="btn btn-primary">수정</a>
                <form action="{{ route('backoffice.menus.destroy', $menu) }}" method="POST" class="delete-form" data-name="{{ $menu->name }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger delete-menu-btn">삭제</button>
                </form>
            </div>
        </li>
        @if($menu->children && $menu->children->count() > 0)
            <li>
                <ul class="submenu-list" data-parent-id="{{ $menu->id }}">
                    @foreach($menu->children as $child)
                        <li class="submenu-item" data-id="{{ $child->id }}">
                            <div class="menu-info">
                                <div class="drag-handle"><i class="fa fa-grip-vertical"></i></div>
                                <span class="menu-name">└ {{ $child->name }}</span>
                                <span class="menu-url">{{ $child->url ?? '-' }}</span>
                                <span class="menu-status">
                                    <span class="status-badge {{ $child->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $child->is_active ? '활성화' : '비활성화' }}
                                    </span>
                                </span>
                            </div>
                            <div class="menu-actions">
                                <a href="{{ route('backoffice.menus.edit', $child) }}" class="btn btn-primary">수정</a>
                                <form action="{{ route('backoffice.menus.destroy', $child) }}" method="POST" class="delete-form" data-name="{{ $child->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger delete-menu-btn">삭제</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @empty
        <li class="no-menu-item">등록된 메뉴가 없습니다.</li>
    @endforelse
</ul>
@endsection

@push('scripts')
<script src="{{ asset('js/menu-management.js') }}"></script>
@endpush
