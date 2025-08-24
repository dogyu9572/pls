@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/menus.css') }}">
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

@if(session('modal_message'))
    <script>
        // 페이지 로드 시 모달 메시지를 sessionStorage에 저장하고 세션에서 제거
        window.modalMessage = "{{ session('modal_message') }}";
        sessionStorage.setItem('showModal', 'true');
        sessionStorage.setItem('modalMessage', "{{ session('modal_message') }}");
    </script>
@endif

<div class="board-container">
    <div class="board-header">        
        <a href="{{ route('backoffice.menus.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> 새 메뉴 추가
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>{{ $pageTitle }}</h6>
        </div>
        <div class="board-card-body">
            <div class="table-responsive">
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
                        <div class="board-btn-group">
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
                                    <div class="board-btn-group">
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
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    @empty
                    <li class="no-menu-item">등록된 메뉴가 없습니다.</li>
                    @endforelse
                </ul>
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
