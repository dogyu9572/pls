@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
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

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>정보 수정</h6>
        </div>
        <div class="board-card-body">
            <form action="{{ route('backoffice.menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="name" class="board-form-label">메뉴 이름 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
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
                                    @if($parentMenu->id != $menu->id)
                                        <option value="{{ $parentMenu->id }}" {{ old('parent_id', $menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>
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
                    <input type="text" class="board-form-control" id="url" name="url" value="{{ old('url', $menu->url) }}">
                    <small class="board-form-text">외부 URL은 http:// 또는 https://로 시작, 내부 경로는 /로 시작.</small>
                    @error('url')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="icon" class="board-form-label">아이콘</label>
                            <input type="text" class="board-form-control" id="icon" name="icon" value="{{ old('icon', $menu->icon) }}">
                            <small class="board-form-text">Font Awesome 아이콘 클래스를 입력하세요. 예: fa-home</small>

                            <div class="icon-picker-wrapper">
                                <div id="icon-preview" class="icon-preview">
                                    @if($menu->icon)
                                        <i class="fa {{ $menu->icon }}"></i>
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
                            <input type="number" class="board-form-control" id="order" name="order" value="{{ old('order', $menu->order) }}" min="0" required>
                            @error('order')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="board-form-label">활성화</label>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">수정</button>
                    <a href="{{ route('backoffice.menus.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/icon-picker.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
@endpush