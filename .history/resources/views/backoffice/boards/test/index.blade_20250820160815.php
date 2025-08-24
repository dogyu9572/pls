@extends('backoffice.layouts.app')

@section('title', '테스트 - 게시글 관리')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>테스트 - 게시글 관리</h4>
                    <div>                       
                        <a href="{{ route('backoffice.boards.posts.create', 'test') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> 새 게시글
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                                        <a href="{{ route('backoffice.boards.posts.show', ['test', $post->id]) }}" class="text-decoration-none">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td>{{ $post->user->name ?? '알 수 없음' }}</td>
                                    <td>{{ $post->view_count ?? 0 }}</td>
                                    <td>{{ $post->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('backoffice.boards.posts.edit', ['test', $post->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('backoffice.boards.posts.destroy', ['test', $post->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
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
    </div>
</div>
@endsection