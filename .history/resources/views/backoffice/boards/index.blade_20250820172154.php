@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection

@section('content')
<!-- 알림 모달 -->
{{-- <div id="alertModal" class="modal">
    <div class="modal-content">
        <div id="modalHeader" class="modal-header">
            <span id="modalTitle">알림</span>
            <span class="close-modal">&times;</span>
        </div>
        <div id="modalBody" class="modal-body">
            <p id="modalMessage"></p>
        </div>
    </div>
</div> --}}

@if(session('success'))
    <div class="alert alert-success hidden-alert">
        {{ session('success') }}
    </div>
@endif

<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.boards.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> 새 게시판
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>{{ $pageTitle }}</h6>
        </div>
        <div class="board-card-body">
            <div class="table-responsive">
                <table class="board-table">
                    <thead>
                        <tr>
                            <th>이름</th>
                            <th class="w150">게시판명</th>
                            <th class="w150">스킨</th>
                            <th class="w150">상태</th>
                            <th class="w150">생성일</th>
                            <th class="w200">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boards as $board)
                        <tr>
                            <td>{{ $board->name }}</td>
                            <td>{{ $board->slug }}</td>
                            <td>{{ $board->skin ? $board->skin->name : '없음' }}</td>
                            <td>
                                <span class="status-badge {{ $board->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $board->is_active ? '활성화' : '비활성화' }}
                                </span>
                            </td>
                            <td>{{ $board->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="board-btn-group">
                                    <a href="{{ route('boards.index', $board->slug) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> 보기
                                    </a>
                                    {{-- <a href="{{ route('backoffice.boards.posts.index', $board->slug) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-list"></i> 게시글
                                    </a> --}}
                                    <a href="{{ route('backoffice.boards.edit', $board) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> 수정
                                    </a>
                                    <form action="{{ route('backoffice.boards.destroy', $board) }}" method="POST" class="d-inline" onsubmit="return confirm('이 게시판을 삭제하시겠습니까? 관련된 모든 데이터가 함께 삭제됩니다.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> 삭제
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">등록된 게시판이 없습니다.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="board-pagination">
                {{ $boards->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 페이지 로드 시 성공 메시지가 있으면 모달로 표시
    document.addEventListener('DOMContentLoaded', function() {
        checkSessionMessage();
    });

    // 세션 메시지 확인 함수
    function checkSessionMessage() {
        const successMessage = document.querySelector('.alert-success');
        if (successMessage && successMessage.textContent.trim()) {
            // 통합 모달 시스템 사용
            if (window.AppUtils && AppUtils.modal) {
                AppUtils.modal.success(successMessage.textContent.trim());
            }
            successMessage.style.display = 'none';
        }
    }
</script>
@endsection
