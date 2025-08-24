@extends('backoffice.layouts.app')

@section('title', '새 메뉴 생성')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/menu-management.css') }}">
@endsection

@section('content')
<form action="{{ route('backoffice.menus.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="name">메뉴 이름</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="parent_id">상위 메뉴</label>
        <select id="parent_id" name="parent_id">
            <option value="">없음 (최상위 메뉴)</option>
            @foreach($menus as $menu)
                <option value="{{ $menu->id }}" {{ old('parent_id') == $menu->id ? 'selected' : '' }}>
                    {{ $menu->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="url">URL</label>
        <input type="text" id="url" name="url" value="{{ old('url') }}">
        <small>외부 URL은 http:// 또는 https://로 시작, 내부 경로는 /로 시작.</small>
        @error('url')
            <div class="error-text">{{ $message }}</div>
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
        <button type="submit" class="btn-submit btn-green">메뉴 생성</button>
        <a href="{{ route('backoffice.menus.index') }}" class="btn-cancel">취소</a>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/icon-picker.js') }}"></script>
@endpush
