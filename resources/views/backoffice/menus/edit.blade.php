@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
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

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.admin-menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>{{ $pageTitle }}</h6>
        </div>
        <div class="board-card-body">
            <form action="{{ route('backoffice.admin-menus.update', $admin_menu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="name" class="board-form-label">메뉴 이름 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name', $admin_menu->name) }}" required>
                            @error('name')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="parent_id" class="board-form-label">상위 메뉴</label>
                            <select class="board-form-control" id="parent_id" name="parent_id">
                                <option value="">없음 (최상위 메뉴)</option>
                                @foreach($menus as $parentMenu)
                                    @if($parentMenu->id != $admin_menu->id)
                                        <option value="{{ $parentMenu->id }}" {{ old('parent_id', $admin_menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>
                                            {{ $parentMenu->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <label for="url" class="board-form-label">URL</label>
                    <div class="url-input-group">
                        <select class="board-form-control url-prefix-select" id="url_prefix">
                            <option value="">직접 입력</option>
                            <option value="admin">관리자</option>
                            <option value="board">게시판</option>
                            <option value="external">외부 URL</option>
                        </select>
                        <input type="text" class="board-form-control" id="url" name="url" value="{{ old('url', $admin_menu->url) }}" placeholder="URL을 입력하거나 위에서 선택하세요">
                    </div>
                    <small class="board-form-text">셀렉트박스에서 선택하면 자동으로 접두사가 추가됩니다.</small>
                    @error('url')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="icon" class="board-form-label">아이콘</label>
                            <input type="text" class="board-form-control" id="icon" name="icon" value="{{ old('icon', $admin_menu->icon) }}">
                            <small class="board-form-text">Font Awesome 아이콘 클래스를 입력하세요. 예: fa-home</small>

                            <div class="icon-picker-wrapper">
                                <div id="icon-preview" class="icon-preview">
                                    @if($admin_menu->icon)
                                        <i class="fa {{ $admin_menu->icon }}"></i>
                                    @endif
                                </div>
                                <button id="show-icon-picker" type="button" class="btn btn-secondary btn-sm">아이콘 선택</button>

                                <div id="icon-picker-container">
                                    <input type="text" id="icon-search" placeholder="아이콘 검색...">
                                    <div id="icon-list"></div>
                                </div>
                            </div>

                            @error('icon')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="order" class="board-form-label">정렬 순서 <span class="required">*</span></label>
                            <input type="number" class="board-form-control" id="order" name="order" value="{{ old('order', $admin_menu->order) }}" min="0" required>
                            @error('order')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', $admin_menu->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="board-form-label">활성화</label>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">수정</button>
                    <a href="{{ route('backoffice.admin-menus.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/backoffice/icon-picker.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // URL 자동 완성 기능
        const urlPrefixSelect = document.getElementById('url_prefix');
        const urlInput = document.getElementById('url');
        
        // 기존 URL 값이 있으면 접두사 자동 감지
        if (urlInput.value) {
            detectUrlPrefix(urlInput.value);
        }
        
        // 셀렉트박스 변경 시 URL 자동 완성
        urlPrefixSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            let prefix = '';
            
            switch(selectedValue) {
                case 'admin':
                    prefix = '/backoffice/';
                    break;
                case 'board':
                    prefix = '/backoffice/board-posts/';
                    break;
                case 'external':
                    prefix = 'https://';
                    break;
                default:
                    prefix = '';
            }
            
            // 기존 URL에서 접두사 제거 후 새로운 접두사 추가
            let currentUrl = urlInput.value;
            if (currentUrl) {
                // 기존 접두사 제거
                currentUrl = currentUrl.replace(/^(\/backoffice\/|\/backoffice\/board-posts\/|https?:\/\/)/, '');
                urlInput.value = prefix + currentUrl;
            } else {
                urlInput.value = prefix;
            }
            
            // URL 입력 필드에 포커스
            urlInput.focus();
        });
        
        // URL 접두사 자동 감지 함수
        function detectUrlPrefix(url) {
            if (url.startsWith('/backoffice/board-posts/')) {
                urlPrefixSelect.value = 'board';
            } else if (url.startsWith('/backoffice/')) {
                urlPrefixSelect.value = 'admin';
            } else if (url.startsWith('http://') || url.startsWith('https://')) {
                urlPrefixSelect.value = 'external';
            } else {
                urlPrefixSelect.value = '';
            }
        }
        
        // 모달 관련 요소
        const alertModal = document.getElementById('alertModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const closeModal = alertModal.querySelector('.close-modal');

        // 성공 메시지가 있으면 모달 표시
        @if(session('success'))
            showAlertModal("알림", "{{ session('success') }}");
        @endif

        // 모달 표시 함수
        function showAlertModal(title, message) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            alertModal.style.display = 'block';
        }

        // 모달 닫기
        closeModal.onclick = function() {
            alertModal.style.display = 'none';
        }

        // 모달 외부 클릭 시 닫기
        window.onclick = function(event) {
            if (event.target == alertModal) {
                alertModal.style.display = 'none';
            }
        }
    });
</script>
<script src="{{ asset('js/backoffice/menu-management.js') }}"></script>
@endsection