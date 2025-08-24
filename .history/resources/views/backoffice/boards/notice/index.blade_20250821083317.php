@extends('backoffice.layouts.app')

@section('title', '공지사항')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <style>
        .board-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .board-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .board-info h4 {
            margin: 0;
            color: #212529;
            font-weight: 600;
        }
        
        .board-count {
            background-color: #e9ecef;
            color: #495057;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .board-buttons {
            display: flex;
            gap: 0.5rem;
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success hidden-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="board-container">
        <div class="board-header">
            <div class="board-info">
                <h4>공지사항</h4>
                <span class="board-count">총 {{ $posts->total() }}개</span>
            </div>
            <div class="board-buttons">
                <button type="button" id="bulk-delete-btn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> 선택 삭제
                </button>
                <a href="{{ route('backoffice.board_posts.create', 'notice') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> 새 게시글
                </a>              
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <h6>공지사항</h6>
            </div>
            <div class="board-card-body">
                <!-- 검색 필터 -->
                <div class="board-filter">
                    <form method="GET" action="{{ route('backoffice.board_posts.index', 'notice') }}" class="filter-form">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label for="start_date" class="filter-label">등록일 시작</label>
                                <input type="date" id="start_date" name="start_date" class="filter-input"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="filter-group">
                                <label for="end_date" class="filter-label">등록일 끝</label>
                                <input type="date" id="end_date" name="end_date" class="filter-input"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="filter-group">
                                <label for="search_type" class="filter-label">검색 구분</label>
                                <select id="search_type" name="search_type" class="filter-select">
                                    <option value="">전체</option>
                                    <option value="title" {{ request('search_type') == 'title' ? 'selected' : '' }}>제목
                                    </option>
                                    <option value="content" {{ request('search_type') == 'content' ? 'selected' : '' }}>내용
                                    </option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="keyword" class="filter-label">검색어</label>
                                <input type="text" id="keyword" name="keyword" class="filter-input"
                                    placeholder="검색어를 입력하세요" value="{{ request('keyword') }}">
                            </div>
                            <div class="filter-group">
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> 검색
                                    </button>
                                    <a href="{{ route('backoffice.board_posts.index', 'notice') }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> 초기화
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th class="w5 checkbox-column">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th class="w5">번호</th>
                                <th class="w10">구분</th>
                                <th>제목</th>
                                <th class="w10">작성자</th>
                                <th class="w10">조회수</th>
                                <th class="w10">작성일</th>
                                <th class="w15">관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                <tr>
                                    <td >
                                        <input type="checkbox" name="selected_posts[]" value="{{ $post->id }}" class="form-check-input post-checkbox">
                                    </td>
                                    <td>
                                        @if ($post->is_notice)
                                            <span class="notice-badge">공지</span>
                                        @else
                                            {{ $posts->total() - ($posts->currentPage() - 1) * $posts->perPage() - $loop->index }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-general">일반</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('backoffice.board_posts.show', ['notice', $post->id]) }}"
                                            class="post-title-link">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                                                         <td>{{ $post->author_name ?? '알 수 없음' }}</td>
                                    <td>{{ $post->view_count ?? 0 }}</td>
                                    <td>{{ $post->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="board-btn-group">
                                            <a href="{{ route('backoffice.board_posts.show', ['notice', $post->id]) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> 보기
                                            </a>
                                            <a href="{{ route('backoffice.board_posts.edit', ['notice', $post->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> 수정
                                            </a>
                                            <form
                                                action="{{ route('backoffice.board_posts.destroy', ['notice', $post->id]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
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
                                    <td colspan="7" class="text-center">등록된 게시글이 없습니다.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <x-pagination :paginator="$posts" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 페이지 로드 시 성공 메시지가 있으면 모달로 표시
        document.addEventListener('DOMContentLoaded', function() {
            checkSessionMessage();
            initBulkActions();
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

        // 일괄 작업 초기화
        function initBulkActions() {
            const selectAllCheckbox = document.getElementById('select-all');
            const postCheckboxes = document.querySelectorAll('.post-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

            // 전체 선택/해제
            selectAllCheckbox.addEventListener('change', function() {
                postCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButton();
            });

            // 개별 체크박스 변경 시
            postCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                    updateBulkDeleteButton();
                });
            });

                    // 일괄 삭제 버튼 클릭
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedPosts = Array.from(postCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedPosts.length === 0) {
                alert('삭제할 게시글을 선택해주세요.');
                return;
            }

            if (confirm(`선택한 ${selectedPosts.length}개의 게시글을 삭제하시겠습니까?`)) {
                bulkDeletePosts(selectedPosts);
            }
        });
        }

        // 전체 선택 체크박스 상태 업데이트
        function updateSelectAllCheckbox() {
            const selectAllCheckbox = document.getElementById('select-all');
            const postCheckboxes = document.querySelectorAll('.post-checkbox');
            const checkedCount = Array.from(postCheckboxes).filter(checkbox => checkbox.checked).length;
            const totalCount = postCheckboxes.length;

            selectAllCheckbox.checked = checkedCount === totalCount;
        }

        // 일괄 삭제 버튼 상태 업데이트
        function updateBulkDeleteButton() {
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const checkedCount = Array.from(document.querySelectorAll('.post-checkbox'))
                .filter(checkbox => checkbox.checked).length;

            bulkDeleteBtn.disabled = checkedCount === 0;
            bulkDeleteBtn.textContent = `선택 삭제 (${checkedCount})`;
        }



        // 일괄 삭제 실행
        function bulkDeletePosts(postIds) {
            const formData = new FormData();
            postIds.forEach(id => formData.append('post_ids[]', id));

            fetch('{{ route("backoffice.board_posts.bulk_destroy", "notice") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('선택한 게시글이 삭제되었습니다.');
                    location.reload();
                } else {
                    alert('삭제 중 오류가 발생했습니다: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('삭제 중 오류가 발생했습니다.');
            });
        }
    </script>
@endpush
