@extends('backoffice.layouts.app')

@section('title', '공지사항 - 게시글 상세보기')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/common/buttons.css') }}">
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
                        <i class="fas fa-user"></i> {{ $post->user->name ?? '알 수 없음' }}
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

<style>
    .board-post-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .board-post-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 1rem;
    }

    .board-post-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .board-post-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .board-post-meta i {
        color: #adb5bd;
    }

    .board-post-notice {
        color: #fd7e14;
        font-weight: 500;
    }

    .board-post-category {
        color: #6f42c1;
        font-weight: 500;
    }

    .board-post-content {
        margin-bottom: 2rem;
        line-height: 1.7;
        color: #212529;
    }

    .board-post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin: 1rem 0;
    }

    .board-post-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }

    .board-post-content table th,
    .board-post-content table td {
        border: 1px solid #dee2e6;
        padding: 0.5rem;
        text-align: left;
    }

    .board-post-content table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .board-post-attachments {
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .board-post-attachments h6 {
        margin-bottom: 1rem;
        color: #495057;
        font-weight: 600;
    }

    .board-attachment-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .board-attachment-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background-color: white;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .board-attachment-item i {
        color: #6c757d;
    }

    .board-attachment-link {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .board-attachment-link:hover {
        text-decoration: underline;
    }

    .board-attachment-size {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .board-post-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    @media (max-width: 768px) {
        .board-post-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .board-post-actions {
            flex-direction: column;
        }

        .board-post-actions .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection