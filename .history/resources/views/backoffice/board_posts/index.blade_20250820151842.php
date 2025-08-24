@extends('backoffice.layouts.app')

@section('title', $board->name . ' - 게시글 관리')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4>{{ $board->name }} - 게시글 관리</h4>
                <p class="text-muted">{{ $board->description }}</p>
            </div>
            <div>
                <a href="{{ route('backoffice.boards.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 게시판 목록
                </a>
                <a href="{{ route('backoffice.boards.posts.create', $board->slug) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> 새 게시글
                </a>
            </div>
        </div>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>게시글 목록</h6>
        </div>
        <div class="board-card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="60">번호</th>
                            <th width="80">구분</th>
                            <th>제목</th>
                            <th width="120">작성자</th>
                            <th width="100">조회수</th>
                            <th width="120">작성일</th>
                            <th width="120">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                @if($post->is_notice)
                                    <span class="badge bg-warning">공지</span>
                                @else
                                    <span class="badge bg-secondary">일반</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('backoffice.boards.posts.show', [$board->slug, $post->id]) }}" class="text-decoration-none">
                                    {{ $post->title }}
                                    @if($post->comments_count > 0)
                                        <span class="text-muted">[{{ $post->comments_count }}]</span>
                                    @endif
                                </a>
                            </td>
                            <td>{{ $post->author_name }}</td>
                            <td>{{ $post->view_count }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('backoffice.boards.posts.edit', [$board->slug, $post->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('backoffice.boards.posts.destroy', [$board->slug, $post->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>등록된 게시글이 없습니다.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($posts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 게시글 관리 관련 스크립트
    document.addEventListener('DOMContentLoaded', function() {
        // 삭제 확인
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('정말 이 게시글을 삭제하시겠습니까?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
