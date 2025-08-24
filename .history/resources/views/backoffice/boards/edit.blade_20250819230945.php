@extends('backoffice.layouts.app')

@section('title', '게시판 수정')

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
        <div class="col-md-8">
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

                            <div class="board-form-col board-form-col-3">
                                <div class="board-form-group">
                                    <label for="list_count" class="board-form-label">페이지당 글 수</label>
                                    <input type="number" class="board-form-control" id="list_count" name="list_count" value="{{ old('list_count', $board->list_count ?? 15) }}" min="5" max="100">
                                </div>
                            </div>

                            <div class="board-form-col board-form-col-3">
                                <div class="board-form-group">
                                    <label for="hot_threshold" class="board-form-label">인기글 기준</label>
                                    <input type="number" class="board-form-control" id="hot_threshold" name="hot_threshold" value="{{ old('hot_threshold', $board->hot_threshold ?? 10) }}" min="0">
                                    <small class="board-form-text">조회수가 이 값 이상이면 인기글로 표시됩니다.</small>
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
                            <label for="is_active" class="board-form-label">사용 여부</label>
                            <div class="board-switch">
                                <input type="checkbox" class="board-switch-input" id="is_active" name="is_active" value="1" {{ old('is_active', $board->is_active) ? 'checked' : '' }}>
                                <span class="board-switch-slider"></span>
                            </div>
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

                        <div class="board-form-footer">
                            <a href="{{ route('backoffice.boards.index') }}" class="board-btn board-btn-secondary">취소</a>
                            <button type="submit" class="board-btn board-btn-primary">저장</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- 게시판 통계 -->
            <div class="board-card">
                <div class="board-card-header">
                    <h6>게시판 통계</h6>
                </div>
                <div class="board-card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="board-stats-box bg-info">
                                <h3>{{ $board->posts->count() }}</h3>
                                <p>총 게시글</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="board-stats-box bg-success">
                                <h3>{{ $board->comments->count() }}</h3>
                                <p>총 댓글</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="font-weight-bold">최근 활동</h6>
                        <p>최신 게시글:
                            @if($board->posts->count() > 0)
                                {{ $board->posts->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                            @else
                                없음
                            @endif
                        </p>
                        <p>최신 댓글:
                            @if($board->comments->count() > 0)
                                {{ $board->comments->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                            @else
                                없음
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- 게시판 바로가기 -->
            <div class="board-card">
                <div class="board-card-header">
                    <h6>게시판 관리</h6>
                </div>
                <div class="board-card-body">
                    <div class="mb-3">
                        <a href="{{ route('boards.index', $board->slug) }}" target="_blank" class="board-btn board-btn-success btn-block">
                            <i class="fas fa-external-link-alt"></i> 게시판 보기
                        </a>
                    </div>
                    <form action="{{ route('backoffice.boards.destroy', $board) }}" method="POST" onsubmit="return confirm('이 게시판을 삭제하시겠습니까? 관련된 모든 데이터가 함께 삭제됩니다.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="board-btn board-btn-danger btn-block">
                            <i class="fas fa-trash"></i> 게시판 삭제
                        </button>
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
