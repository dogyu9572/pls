<div class="comments-section">
    <h3 class="comments-title">댓글 {{ $post->comments->count() }}개</h3>

    @if($post->comments->count() > 0)
        <div class="comment-list">
            @foreach($post->comments as $comment)
                <div class="comment-item" id="comment-{{ $comment->id }}">
                    <div class="comment-header">
                        <div class="comment-author">
                            {{ $comment->author_name ?? '익명' }}
                        </div>
                        <div class="comment-meta">
                            <span class="comment-date">
                                {{ $comment->created_at->format('Y-m-d H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="comment-content">
                        @if($comment->is_secret && !$canViewSecret)
                            <p><i class="fas fa-lock"></i> 비밀 댓글입니다.</p>
                        @else
                            <p>{!! nl2br(e($comment->content)) !!}</p>
                        @endif
                    </div>

                    <div class="comment-actions">
                        @if(auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->isAdmin()))
                            <form action="{{ route('boards.comments.destroy', [$board->slug, $post->id, $comment->id]) }}" method="POST" class="delete-comment-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-text btn-sm">삭제</button>
                            </form>
                        @else
                            <a href="javascript:;" class="btn btn-text btn-sm show-password-form" data-comment-id="{{ $comment->id }}">수정/삭제</a>

                            <div class="comment-password-form" id="password-form-{{ $comment->id }}">
                                <form action="{{ route('boards.comments.check_password', [$board->slug, $post->id, $comment->id]) }}" method="POST">
                                    @csrf
                                    <div class="form-inline">
                                        <input type="password" name="password" class="form-control form-control-sm" placeholder="비밀번호" required>
                                        <button type="submit" class="btn btn-sm btn-primary">확인</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="no-comments">등록된 댓글이 없습니다.</p>
    @endif

    @if($board->settings->use_comment)
        <div class="comment-form">
            <h4>댓글 작성</h4>
            <form action="{{ route('boards.comments.store', [$board->slug, $post->id]) }}" method="POST">
                @csrf

                @if(!auth()->check())
                    <div class="guest-comment-info">
                        <div class="form-group">
                            <label for="author_name">이름</label>
                            <input type="text" id="author_name" name="author_name" class="form-control @error('author_name') is-invalid @enderror" value="{{ old('author_name') }}" required>
                            @error('author_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">비밀번호</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="댓글을 입력하세요..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="is_secret" name="is_secret" value="1" {{ old('is_secret') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_secret">비밀 댓글</label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">댓글 등록</button>
                </div>
            </form>
        </div>
    @endif
</div>

@push('scripts')
<script src="{{ asset('js/backoffice/comments.js') }}"></script>
@endpush
