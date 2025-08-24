@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/board-skins.css') }}">
@endsection

@section('content')
<div class="skin-container">
    <div class="skin-header">
        <h1>{{ $boardSkin->name }} 스킨 수정</h1>
        <a href="{{ route('backoffice.board_skins.index') }}" class="skin-btn skin-btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="skin-form">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <form action="{{ route('backoffice.board_skins.update', $boardSkin) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="name">스킨 이름 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $boardSkin->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="directory">디렉토리 이름 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="directory" name="directory" value="{{ old('directory', $boardSkin->directory) }}" required>
                        <small class="form-text text-muted">영문, 숫자, 대시(-), 언더스코어(_)만 사용 가능합니다.</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">설명</label>
                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $boardSkin->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="thumbnail">썸네일 이미지</label>
                        @if ($boardSkin->thumbnail)
                            <div class="mb-3">
                                <img src="{{ $boardSkin->thumbnail }}" alt="{{ $boardSkin->name }}" class="skin-edit-thumbnail img-thumbnail">
                                <p class="small mt-1 mb-0">현재 이미지</p>
                            </div>
                        @endif
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">새 이미지를 업로드하면 기존 이미지가 대체됩니다.</small>

                        <div class="mt-3 d-none" id="thumbnail-preview-container">
                            <img id="thumbnail-preview" src="#" alt="썸네일 미리보기" style="max-width: 100%; max-height: 200px;" class="border">
                            <p class="small mt-1 mb-0">새 이미지 미리보기</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="options">추가 옵션 (JSON)</label>
                        <textarea class="form-control" id="options" name="options" rows="3">{{ old('options', $boardSkin->options ?? '{}') }}</textarea>
                        <small class="form-text text-muted">스킨에서 사용할 추가 옵션을 JSON 형식으로 입력합니다.</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $boardSkin->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_active">사용 여부</label>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" value="1" {{ old('is_default', $boardSkin->is_default) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_default">기본 스킨으로 설정</label>
                    <small class="form-text text-muted">기본 스킨으로 설정하면 기존 기본 스킨은 해제됩니다.</small>
                </div>
            </div>

            <hr>
            <div class="text-right">
                <a href="{{ route('backoffice.board_skins.index') }}" class="skin-btn skin-btn-secondary">취소</a>
                <button type="submit" class="skin-btn skin-btn-primary">저장</button>
            </div>
        </form>
    </div>

    <div class="skin-form mt-4">
        <h3 class="form-label mb-4">템플릿 편집</h3>
        <p>이 스킨의 각 페이지 템플릿을 편집할 수 있습니다:</p>
        <div class="template-buttons">
            <a href="{{ route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => 'list']) }}" class="skin-btn skin-btn-info">
                <i class="fas fa-list"></i> 목록 템플릿
            </a>
            <a href="{{ route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => 'view']) }}" class="skin-btn skin-btn-info">
                <i class="fas fa-eye"></i> 상세보기 템플릿
            </a>
            <a href="{{ route('backoffice.board_skins.edit_template', ['boardSkin' => $boardSkin->id, 'template' => 'write']) }}" class="skin-btn skin-btn-info">
                <i class="fas fa-edit"></i> 글쓰기 템플릿
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/board-skins.js') }}"></script>
@endsection
