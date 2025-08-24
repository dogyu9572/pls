@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>정보 입력</h6>
        </div>
        <div class="board-card-body">
            <form action="{{ route('backoffice.menus.store') }}" method="POST">
                @csrf

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="name" class="board-form-label">메뉴 이름 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name') }}" required>
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
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}" {{ old('parent_id') == $menu->id ? 'selected' : '' }}>
                                        {{ $menu->name }}
                                    </option>
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
                        <select class="board-form-control url-prefix-select" id="url_prefix" style="width: 150px; margin-right: 10px;">
                            <option value="">직접 입력</option>
                            <option value="admin">관리자</option>
                            <option value="board">게시판</option>
                            <option value="external">외부 URL</option>
                        </select>
                        <input type="text" class="board-form-control" id="url" name="url" value="{{ old('url') }}" placeholder="URL을 입력하거나 위에서 선택하세요">
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
                            <input type="text" class="board-form-control" id="icon" name="icon" value="{{ old('icon') }}">
                            <small class="board-form-text">Font Awesome 아이콘 클래스를 입력하세요. 예: fa-home</small>

                            <div class="icon-picker-wrapper">
                                <div id="icon-preview" class="icon-preview"></div>
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
                            <input type="number" class="board-form-control" id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                            @error('order')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="board-form-label">활성화</label>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">생성</button>
                    <a href="{{ route('backoffice.menus.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/icon-picker.js') }}"></script>
<script src="{{ asset('js/menu-management.js') }}"></script>
@endsection
