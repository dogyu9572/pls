@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">댓글 비밀번호 확인</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST">
                        @csrf

                        <div class="form-group">
                            <p class="mb-3">댓글을 삭제하기 위해 작성 시 입력했던 비밀번호를 입력해주세요.</p>

                            <label for="password">비밀번호</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group text-center mt-4">
                            <a href="{{ route('boards.show', [$board->slug, $post->id]) }}" class="btn btn-secondary">취소</a>
                            <button type="submit" class="btn btn-primary">확인</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
