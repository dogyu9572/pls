@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board_skins.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>스킨 정보 입력</h6>
        </div>
        <div class="board-card-body">
            @if ($errors->any())
                <div class="board-alert board-alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('backoffice.board_skins.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="name" class="board-form-label">스킨 이름 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="directory" class="board-form-label">디렉토리명 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="directory" name="directory" value="{{ old('directory') }}" placeholder="영문, 숫자, 언더스코어만 사용 가능합니다" required>
                            <small class="board-form-text">스킨 파일이 저장될 디렉토리 이름입니다. 영문, 숫자, 언더스코어만 사용 가능합니다.</small>
                            @error('directory')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <label for="description" class="board-form-label">설명</label>
                    <textarea class="board-form-control board-form-textarea" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="skin_file" class="board-form-label">스킨 파일 <span class="required">*</span></label>
                    <input type="file" class="board-form-control" id="skin_file" name="skin_file" accept=".zip" required>
                    <small class="board-form-text">ZIP 파일만 업로드 가능합니다. 최대 10MB</small>
                    @error('skin_file')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <div class="board-checkbox-item">
                        <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="board-form-label">활성화</label>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">저장</button>
                    <a href="{{ route('backoffice.board_skins.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection