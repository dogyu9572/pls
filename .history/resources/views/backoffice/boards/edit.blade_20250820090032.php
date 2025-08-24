@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <h1>{{ $board->name }} 게시판 수정</h1>
        <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">목록으로</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="board-alert board-alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="board-card">
                <div class="board-card-header">
                    <h6>게시판 정보 수정</h6>
                </div>
                <div class="board-card-body">
                    <form action="{{ route('backoffice.boards.update', $board) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="name" class="board-form-label">게시판 이름 <span class="required">*</span></label>
                                    <input type="text" class="board-form-control" id="name" name="name" value="{{ old('name', $board->name) }}" required>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="slug" class="board-form-label">URL <span class="required">*</span></label>
                                    <input type="text" class="board-form-control" id="slug" name="slug" value="{{ old('slug', $board->slug) }}" required>
                                    <small class="board-form-text">영문, 숫자, 대시(-), 언더스코어(_)만 사용 가능합니다.</small>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <label for="description" class="board-form-label">설명</label>
                            <textarea class="board-form-control board-form-textarea" id="description" name="description" rows="2">{{ old('description', $board->description) }}</textarea>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="skin_id" class="board-form-label">게시판 스킨 <span class="required">*</span></label>
                                    <select class="board-form-control" id="skin_id" name="skin_id" required>
                                        <option value="">스킨을 선택하세요</option>
                                        @foreach ($skins as $skin)
                                            <option value="{{ $skin->id }}" {{ old('skin_id', $board->skin_id) == $skin->id ? 'selected' : '' }}>
                                                {{ $skin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label for="list_count" class="board-form-label">페이지당 글 수</label>
                                    <input type="number" class="board-form-control" id="list_count" name="list_count" value="{{ old('list_count', $board->list_count ?? 15) }}" min="5" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-4">
                                <div class="board-form-group">
                                    <label class="board-form-label">읽기 권한 <span class="required">*</span></label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_read_all" name="permission_read" value="all" class="board-radio-input" {{ old('permission_read', $board->permission_read) == 'all' ? 'checked' : '' }}>
                                            <label for="permission_read_all">모두</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_read_member" name="permission_read" value="member" class="board-radio-input" {{ old('permission_read', $board->permission_read) == 'member' ? 'checked' : '' }}>
                                            <label for="permission_read_member">회원만</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_read_admin" name="permission_read" value="admin" class="board-radio-input" {{ old('permission_read', $board->permission_read) == 'admin' ? 'checked' : '' }}>
                                            <label for="permission_read_admin">관리자만</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-4">
                                <div class="board-form-group">
                                    <label class="board-form-label">쓰기 권한 <span class="required">*</span></label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_write_all" name="permission_write" value="all" class="board-radio-input" {{ old('permission_write', $board->permission_write) == 'all' ? 'checked' : '' }}>
                                            <label for="permission_write_all">모두</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_write_member" name="permission_write" value="member" class="board-radio-input" {{ old('permission_write', $board->permission_write) == 'member' ? 'checked' : '' }}>
                                            <label for="permission_write_member">회원만</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_write_admin" name="permission_write" value="admin" class="board-radio-input" {{ old('permission_write', $board->permission_write) == 'admin' ? 'checked' : '' }}>
                                            <label for="permission_write_admin">관리자만</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-4">
                                <div class="board-form-group">
                                    <label class="board-form-label">댓글 작성 권한 <span class="required">*</span></label>
                                    <div class="board-radio-group">
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_comment_all" name="permission_comment" value="all" class="board-radio-input" {{ old('permission_comment', $board->permission_comment) == 'all' ? 'checked' : '' }}>
                                            <label for="permission_comment_all">모두</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_comment_member" name="permission_comment" value="member" class="board-radio-input" {{ old('permission_comment', $board->permission_comment) == 'member' ? 'checked' : '' }}>
                                            <label for="permission_comment_member">회원만</label>
                                        </div>
                                        <div class="board-radio-item">
                                            <input type="radio" id="permission_comment_admin" name="permission_comment" value="admin" class="board-radio-input" {{ old('permission_comment', $board->permission_comment) == 'admin' ? 'checked' : '' }}>
                                            <label for="permission_comment_admin">관리자만</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', $board->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="board-form-label">사용 여부</label>
                            </div>
                        </div>

                        <div class="board-form-group">
                            <div class="board-checkbox-item">
                                <input type="checkbox" class="board-checkbox-input" id="enable_notice" name="enable_notice" value="1" {{ old('enable_notice', $board->enable_notice ?? '0') == '1' ? 'checked' : '' }}>
                                <label for="enable_notice" class="board-form-label">공지 기능 활성화</label>
                            </div>
                            <small class="board-form-text">체크하면 게시글 작성 시 공지여부를 설정할 수 있습니다. 공지글은 최상단에 표시됩니다.</small>
                        </div>

                        <div class="board-form-row">
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label class="board-form-label">생성일</label>
                                    <div class="board-form-text">{{ $board->created_at->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                            <div class="board-form-col board-form-col-6">
                                <div class="board-form-group">
                                    <label class="board-form-label">마지막 수정일</label>
                                    <div class="board-form-text">{{ $board->updated_at->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="board-form-actions">
                            <button type="submit" class="btn btn-primary">수정</button>
                            <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">취소</a>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        // 이미 값이 있는 경우 자동 생성 비활성화
        if (nameInput && slugInput && !slugInput.value) {
            nameInput.addEventListener('input', function() {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                slugInput.value = slug;
            });
        }
    });
</script>
@endsection
