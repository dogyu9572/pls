@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/board-skins.css') }}">
@endsection

@section('content')
<div class="skin-container">
    <div class="skin-header">
        <h1>새 게시판 스킨 추가</h1>
        <a href="{{ route('backoffice.board_skins.index') }}" class="skin-btn skin-btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로 돌아가기
        </a>
    </div>

    <div class="skin-form">
        <form method="POST" action="{{ route('backoffice.board_skins.store') }}" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label for="name">스킨 이름 <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="directory">디렉토리명 <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('directory') is-invalid @enderror" id="directory" name="directory" value="{{ old('directory') }}" placeholder="영문, 숫자, 언더스코어만 사용 가능합니다" required>
                <small class="text-muted">스킨 파일이 저장될 디렉토리 이름입니다. 영문, 숫자, 언더스코어만 사용 가능합니다.</small>
                @error('directory')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">설명</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="thumbnail">대표 이미지</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                    <label class="custom-file-label" for="thumbnail">이미지 선택...</label>
                </div>
                <small class="text-muted">대표 이미지는 선택사항입니다. 권장 크기: 800x600px</small>
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">활성화</label>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_default">기본 스킨으로 설정</label>
                </div>
                <small class="text-muted">기본 스킨으로 설정하면 기존 기본 스킨은 해제됩니다.</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="skin-btn skin-btn-primary">
                    <i class="fas fa-save"></i> 스킨 저장
                </button>
                <a href="{{ route('backoffice.board_skins.index') }}" class="skin-btn skin-btn-secondary">
                    <i class="fas fa-times"></i> 취소
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/board-skins.js') }}"></script>
@endsection
