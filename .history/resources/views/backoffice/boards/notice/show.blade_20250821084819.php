@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 상세보기')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/backoffice/boards.css') }}">
@endsection

@section('content')
<div class="board-container">
    <div class="board-header">
        <a href="{{ route('backoffice.board_posts.index', 'notice') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> 목록으로
        </a>
    </div>

    <div class="board-card">
        <div class="board-card-header">
            <h6>게시글 상세보기</h6>
        </div>
        <div class="board-card-body">
            <div class="board-post-header">
                <h4 class="board-post-title">{{ $post->title }}</h4>
                <div class="board-post-meta">
                    <span class="board-post-author">
                        <i class="fas fa-user"></i> {{ $post->author_name ?? '알 수 없음' }}
                    </span>
                    <span class="board-post-date">
                        <i class="fas fa-calendar"></i> {{ $post->created_at->format('Y-m-d H:i') }}
                    </span>
                    <span class="board-post-views">
                        <i class="fas fa-eye"></i> 조회수 {{ $post->view_count ?? 0 }}
                    </span>
                    @if($post->is_notice)
                        <span class="board-post-notice">
                            <i class="fas fa-bullhorn"></i> 공지
                        </span>
                    @endif
                    @if($post->category)
                        <span class="board-post-category">
                            <i class="fas fa-tag"></i> {{ $post->category }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="board-post-content">
                {!! $post->content !!}
            </div>

            @if($post->attachments)
                <div class="board-post-attachments">
                    <h6><i class="fas fa-paperclip"></i> 첨부파일</h6>
                    <div class="board-attachment-list">
                        @php
                            $attachments = json_decode($post->attachments, true);
                        @endphp
                        @if($attachments && is_array($attachments))
                            @foreach($attachments as $attachment)
                                <div class="board-attachment-item">
                                    <i class="fas fa-file"></i>
                                    <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="board-attachment-link">
                                        {{ $attachment['name'] }}
                                    </a>
                                    <span class="board-attachment-size">({{ number_format($attachment['size'] / 1024 / 1024, 2) }}MB)</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            <div class="board-post-actions">
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
    </div>
</div>
@endsection