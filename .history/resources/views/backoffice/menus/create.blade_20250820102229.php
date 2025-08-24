@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.menus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>메뉴 정보 입력</h6>
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
                    <input type="text" class="board-form-control" id="url" name="url" value="{{ old('url') }}">
                    <small class="board-form-text">외부 URL은 http:// 또는 https://로 시작, 내부 경로는 /로 시작.</small>
                    @error('url')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

    <div class="form-group">
        <label for="icon">아이콘</label>
        <input type="text" id="icon" name="icon" value="{{ old('icon') }}">
        <small>Font Awesome 아이콘 클래스를 입력하세요. 예: fa-home</small>

        <div class="icon-picker-wrapper">
            <div id="icon-preview" class="icon-preview"></div>
            <button id="show-icon-picker" type="button">아이콘 선택</button>

            <div id="icon-picker-container">
                <input type="text" id="icon-search" placeholder="아이콘 검색...">
                <div id="icon-list"></div>
            </div>
        </div>

        @error('icon')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="order">정렬 순서</label>
        <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
        @error('order')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
        <label for="is_active">활성화</label>
    </div>

    <div>
        <button type="submit" class="btn-submit btn-green">생성</button>
        <a href="{{ route('backoffice.menus.index') }}" class="btn-cancel">취소</a>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/icon-picker.js') }}"></script>
@endpush
