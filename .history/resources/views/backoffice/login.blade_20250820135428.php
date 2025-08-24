<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>백오피스 로그인</title>
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <!-- 로고 이미지가 있다면 사용하거나, 없다면 제목만 표시 -->
            <!-- <img src="{{ asset('images/logo.png') }}" alt="백오피스 로고"> -->
        </div>

        <div class="login-header">
            <h2>ADMIN LOGIN</h2>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <form class="login-form" action="{{ url('/backoffice/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="login_id">로그인 ID</label>
                <input type="text" id="login_id" name="login_id" required value="{{ old('login_id') }}" placeholder="로그인 ID를 입력하세요">
            </div>
            <div class="form-group">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" required placeholder="비밀번호를 입력하세요">
            </div>
            <div class="form-group">
                <button type="submit">로그인</button>
            </div>
        </form>
    </div>
</body>
</html>
