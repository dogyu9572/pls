@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board-skins.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>{{ $pageTitle }}</h6>
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

            @if (session('success'))
                <div class="board-alert board-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('backoffice.board-skins.update', $boardSkin) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="name" class="board-form-label">스킨 이름 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name', $boardSkin->name) }}" required>
                            @error('name')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <label for="directory" class="board-form-label">디렉토리명 <span class="required">*</span></label>
                            <input type="text" class="board-form-control" id="directory" name="directory" value="{{ old('directory', $boardSkin->directory) }}" required readonly>
                            <small class="board-form-text">디렉토리명은 수정할 수 없습니다.</small>
                            @error('directory')
                                <div class="board-alert board-alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="board-form-group">
                    <label for="description" class="board-form-label">설명</label>
                    <textarea class="board-form-control board-form-textarea" id="description" name="description" rows="3">{{ old('description', $boardSkin->description) }}</textarea>
                    @error('description')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-group">
                    <label for="skin_file" class="board-form-label">스킨 파일 업데이트</label>
                    <input type="file" class="board-form-control" id="skin_file" name="skin_file" accept=".zip">
                    <small class="board-form-text">새 스킨 파일을 업로드하려면 ZIP 파일을 선택하세요. 최대 10MB (선택사항)</small>
                    @error('skin_file')
                        <div class="board-alert board-alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="board-form-row">
                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', $boardSkin->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="board-form-label">활성화</label>
                            </div>
                        </div>
                    </div>

                    <div class="board-form-col board-form-col-6">
                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" id="is_default" name="is_default" value="1" {{ old('is_default', $boardSkin->is_default) ? 'checked' : '' }}>
                                <label for="is_default" class="board-form-label">기본 스킨</label>
                            </div>
                            <small class="board-form-text">기본 스킨으로 설정하면 다른 모든 스킨의 기본 설정이 해제됩니다.</small>
                        </div>
                    </div>
                </div>

                <div class="board-form-actions">
                    <button type="submit" class="btn btn-primary">수정</button>
                    <a href="{{ route('backoffice.board-skins.index') }}" class="btn btn-secondary">취소</a>
                </div>
            </form>
        </div>
    </div>

    <!-- 스킨 정보 -->
    <div class="board-card">
        <div class="board-card-header">
            <h6>스킨 정보</h6>
        </div>
        <div class="board-card-body">
            <div class="board-timestamps">
                <p><strong>생성일:</strong> {{ $boardSkin->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>수정일:</strong> {{ $boardSkin->updated_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>경로:</strong> {{ $boardSkin->getSkinsPath() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection