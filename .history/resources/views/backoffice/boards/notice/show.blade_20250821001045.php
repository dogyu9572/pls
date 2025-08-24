@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 상세보기')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>공지사항 - 게시글 상세보기</h4>
                    <div>
                        <a href="{{ route('backoffice.board_posts.index', 'notice') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> 목록으로
                        </a>
                        <a href="{{ route('backoffice.board_posts.edit', ['notice', $post->id]) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> 수정
                        </a>
                        <form action="{{ route('backoffice.board_posts.destroy', ['notice', $post->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> 삭제
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <div class="text-muted">
                            <small>
                                작성자: {{ $post->user->name ?? '알 수 없음' }} | 
                                작성일: {{ $post->created_at->format('Y-m-d H:i') }} | 
                                조회수: {{ $post->view_count ?? 0 }}
                                @if($post->is_notice)
                                    | <span class="badge bg-warning">공지</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="card-text">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection