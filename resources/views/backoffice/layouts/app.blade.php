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
                <a href="#" id="sidebarToggle" class="sidebar-toggle" style="display: flex !important; align-items: center !important;">
                    <div class="hamburger" style="width: 20px !important; height: 16px !important; display: flex !important; flex-direction: column !important; justify-content: space-between !important;">
                        <span style="display: block !important; width: 100% !important; height: 2px !important; background-color: #333333 !important; border-radius: 1px !important;"></span>
                        <span style="display: block !important; width: 100% !important; height: 2px !important; background-color: #333333 !important; border-radius: 1px !important;"></span>
                        <span style="display: block !important; width: 100% !important; height: 2px !important; background-color: #333333 !important; border-radius: 1px !important;"></span>
                    </div>
                </a>
                <h2>@yield('title', '백오피스')</h2>

                <!-- 유저 드롭다운과 세션 타이머를 함께 배치 -->
                <div class="user-dropdown">
                    <span>{{ auth()->user()->name ?? '관리자' }}님 <span class="session-timer" id="sessionTimer">
                        <i class="fas fa-clock"></i>
                        <span class="session-timer-text"><span id="sessionTimeLeft">--:--</span></span>
                    </span>
                    <button class="session-extend-btn" id="sessionExtendBtn" title="30분 연장">
                        연장
                    </button></span>
                    <div class="dropdown-content">
                        <a href="{{ route('backoffice.admins.edit', auth()->user()->id) }}">정보수정</a>
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
        // 전역 변수로 설정
        window.sessionConfig = {
            lifetime: {{ config('session.lifetime', 120) }} // 세션 타임아웃(분 단위)
        };
        window.logoutUrl = "{{ url('/backoffice/logout') }}"; // 로그아웃 URL
    </script>
    <!-- SortableJS 라이브러리 -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <script src="{{ asset('js/admin/session-timer.js') }}"></script>
    <script src="{{ asset('js/backoffice.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
