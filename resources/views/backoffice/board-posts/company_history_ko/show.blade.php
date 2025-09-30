@extends('backoffice.layouts.app')

@section('title', $board->name ?? '게시판')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/common/modal.css') }}">
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success board-hidden-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="board-container">
        <div class="board-page-header">
            <div class="board-page-buttons">
                <a href="{{ route('backoffice.board-posts.index', $board->slug ?? 'notice') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 목록으로
                </a>
            </div>
        </div>

        <div class="board-card">
            <div class="board-card-header">
                <div class="board-page-card-title">
                    <h6>국문연혁관리</h6>
                </div>
            </div>
            <div class="board-card-body">
                <div class="board-post-header">
                    <div class="board-post-title">
                        <h3>{{ $post->title }}</h3>
                    </div>
                    <div class="board-post-meta">
                        <span class="board-post-author">작성자: {{ $post->author_name ?? '알 수 없음' }}</span>
                        <span class="board-post-date">작성일: {{ $post->created_at->format('Y-m-d H:i') }}</span>
                        <span class="board-post-views">조회수: {{ $post->view_count ?? 0 }}</span>
                    </div>
                </div>

                @if ($post->is_notice)
                    <div class="board-post-notice">
                        <span class="badge badge-warning">공지</span>
                    </div>
                @endif

                @if ($post->category)
                    <div class="board-post-category">
                        <span class="badge badge-info">{{ $post->category }}</span>
                    </div>
                @endif

                <div class="board-post-content">
                    {!! $post->content !!}
                </div>

                <!-- 커스텀 필드 정보 표시 -->
                @if($board->custom_fields_config && $post->custom_fields)
                    @php
                        $customFields = json_decode($post->custom_fields, true);
                    @endphp
                    @if($customFields && is_array($customFields))
                        <div class="board-post-custom-fields">
                            <h6>추가 정보</h6>
                            <div class="board-custom-fields-list">
                                @foreach($board->custom_fields_config as $fieldConfig)
                                    @if(isset($customFields[$fieldConfig['name']]) && !empty($customFields[$fieldConfig['name']]))
                                        @php
                                            $fieldValue = $customFields[$fieldConfig['name']];
                                            $displayValue = match($fieldConfig['type']) {
                                                'date' => \Carbon\Carbon::parse($fieldValue)->format('Y-m-d'),
                                                'checkbox' => $fieldValue == '1' ? '예' : '아니오',
                                                default => $fieldValue
                                            };
                                        @endphp
                                        <div class="board-custom-field-item">
                                            <span class="board-custom-field-label">{{ $fieldConfig['label'] }}:</span>
                                            <span class="board-custom-field-value">{{ $displayValue }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                @if ($post->attachments)
                    <div class="board-post-attachments">
                        <h6>첨부파일</h6>
                        <div class="board-attachment-list">
                            @php
                                $attachments = json_decode($post->attachments, true);
                            @endphp
                            @if (is_array($attachments))
                                @foreach ($attachments as $attachment)
                                    <div class="board-attachment-item">
                                        <i class="fas fa-file"></i>
                                        <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                           class="board-attachment-link" 
                                           target="_blank"
                                           download="{{ $attachment['name'] }}">
                                            {{ $attachment['name'] }}
                                        </a>
                                        <span class="board-attachment-size">
                                            ({{ number_format($attachment['size'] / 1024 / 1024, 2) }}MB)
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <div class="board-post-actions">
                    <a href="{{ route('backoffice.board-posts.edit', [$board->slug ?? 'notice', $post->id]) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> 수정
                    </a>
                    <form action="{{ route('backoffice.board-posts.destroy', [$board->slug ?? 'notice', $post->id]) }}" 
                          method="POST" class="d-inline" 
                          onsubmit="return confirm('정말 이 게시글을 삭제하시겠습니까?');">
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

@section('scripts')
    <script src="{{ asset('js/backoffice/board-posts.js') }}"></script>
@endsection