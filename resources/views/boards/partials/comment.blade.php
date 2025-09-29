<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-info">
        <span class="author">{{ $comment->author_name }}</span>
        <span class="date">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
    </div>

    <div class="comment-content">
        @if($comment->is_secret && !(auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->isAdmin())))
            <p class="secret-comment">비밀 댓글입니다.</p>
        @else
            {!! nl2br(e($comment->content)) !!}
        @endif
    </div>

    <div class="comment-actions">
        @if(auth()->check() || $board->permission_comment == 'all')
            <button type="button" class="btn-reply" data-toggle="reply-form" data-target="reply-form-{{ $comment->id }}">
                답글
            </button>
        @endif

        @if(auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->isAdmin()))
            <form action="{{ route('boards.comments.destroy', [$board->slug, $comment->post_id, $comment->id]) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('이 댓글을 삭제하시겠습니까?')">삭제</button>
            </form>
        @elseif(!auth()->check() && $comment->password)
            <a href="{{ route('boards.comments.password_check', [$board->slug, $comment->post_id, $comment->id]) }}" class="btn-delete">
                삭제
            </a>
        @endif
    </div>

    <!-- 답글 작성 폼 (기본적으로 숨겨짐) -->
    <div id="reply-form-{{ $comment->id }}" class="reply-form">
        <form action="{{ route('boards.comments.store', [$board->slug, $comment->post_id]) }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">

            <textarea name="content" rows="2" placeholder="답글을 입력하세요..." required></textarea>

            @guest
                <div class="guest-info">
                    <input type="text" name="author_name" placeholder="이름" required>
                    <input type="password" name="password" placeholder="비밀번호" required>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_secret_{{ $comment->id }}" name="is_secret" value="1">
                        <label class="form-check-label" for="is_secret_{{ $comment->id }}">비밀 댓글</label>
                    </div>
                </div>
            @endguest

            <button type="submit">답글 작성</button>
            <button type="button" class="btn-cancel" data-toggle="reply-form" data-target="reply-form-{{ $comment->id }}">취소</button>
        </form>
    </div>

    <!-- 대댓글 목록 -->
    @if($comment->replies->count() > 0)
        <div class="replies">
            @foreach($comment->replies as $reply)
                <div class="comment reply" id="comment-{{ $reply->id }}">
                    <div class="comment-info">
                        <span class="author">{{ $reply->author_name }}</span>
                        <span class="date">{{ $reply->created_at->format('Y-m-d H:i') }}</span>
                    </div>

                    <div class="comment-content">
                        @if($reply->is_secret && !(auth()->check() && (auth()->id() == $reply->user_id || auth()->user()->isAdmin())))
                            <p class="secret-comment">비밀 댓글입니다.</p>
                        @else
                            {!! nl2br(e($reply->content)) !!}
                        @endif
                    </div>

                    <div class="comment-actions">
                        @if(auth()->check() && (auth()->id() == $reply->user_id || auth()->user()->isAdmin()))
                            <form action="{{ route('boards.comments.destroy', [$board->slug, $reply->post_id, $reply->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('이 댓글을 삭제하시겠습니까?')">삭제</button>
                            </form>
                        @elseif(!auth()->check() && $reply->password)
                            <a href="{{ route('boards.comments.password_check', [$board->slug, $reply->post_id, $reply->id]) }}" class="btn-delete">
                                삭제
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
