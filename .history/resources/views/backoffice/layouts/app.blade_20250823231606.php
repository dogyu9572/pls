<!DOCTYPE html>
<html lang="ko">
<head>
    @include('backoffice.layouts.header')
    <link rel="stylesheet" href="{{ asset('css/session-timer.css') }}">
    @yield('styles')
</head>
<body>
    <!-- 모바일에서 사이드바 활성화 시 표시되는 백드롭 -->
    <div class="backdrop" id="backdrop"></div>

    <div class="dashboard-container">
        @include('backoffice.layouts.sidebar')

        <div class="content">
            <div class="header">
                <!-- 모바일에서만 보이는 사이드바 토글 버튼 -->
                <button id="sidebarToggle" class="sidebar-toggle d-md-none">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>@yield('title', '백오피스')</h2>

                <!-- 유저 드롭다운과 세션 타이머를 함께 배치 -->
                <div class="user-dropdown">
                    <span>{{ auth()->user()->name ?? '관리자' }}님 <span class="session-timer" id="sessionTimer">
                        <i class="fas fa-clock"></i>
                        <span class="session-timer-text"><span id="sessionTimeLeft">--:--</span></span>
                    </span></span>
                    <div class="dropdown-content">
                        <a href="#">정보수정</a>
                        <a href="{{ url('/backoffice/logout') }}">로그아웃</a>
                    </div>
                </div>
            </div>
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    @include('backoffice.layouts.footer')

    <!-- 세션 타이머 구성 및 로그아웃 URL -->
    <script>
        // 세션 설정 객체
        const sessionConfig = {
            lifetime: {{ config('session.lifetime', 120) }} // 세션 타임아웃(분 단위)
        };
        const logoutUrl = "{{ url('/backoffice/logout') }}"; // 로그아웃 URL
    </script>
    <script src="{{ asset('js/admin/session-timer.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
