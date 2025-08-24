@extends('backoffice.layouts.app')

@section('title', $pageTitle ?? '')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/backoffice/dashboard.css') }}">
@endsection

@section('content')
<div class="dashboard-content">
    <!-- 대시보드 헤더 -->
    <div class="dashboard-header">
        <div class="dashboard-welcome">
            <p>{{ auth()->user()->name ?? '관리자' }}님, 환영합니다!</p>
            <p>{{ date('Y년 m월 d일') }} 백오피스 대시보드 현황입니다.</p>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('backoffice.setting.index') }}" class="dashboard-action-btn">
                <i class="fas fa-cog"></i> 환경설정
            </a>
            <a href="{{ url('/') }}" target="_blank" class="dashboard-action-btn">
                <i class="fas fa-home"></i> 사이트 방문
            </a>
        </div>
    </div>

    <!-- 통계 요약 -->
    <div class="stats-row">
        <div class="stat-card stat-users">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>회원</h3>
                <p class="stat-number">{{ \App\Models\User::count() }}</p>
            </div>
        </div>

        <div class="stat-card stat-boards">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3>게시판</h3>
                <p class="stat-number">{{ \App\Models\Board::count() }}</p>
            </div>
        </div>

        <div class="stat-card stat-posts">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3>게시글</h3>
                <p class="stat-number">{{ $totalPosts ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card stat-comments">
            <div class="stat-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div class="stat-info">
                <h3>댓글</h3>
                <p class="stat-number">{{ $totalComments ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- 데이터 그리드 -->
    <div class="dashboard-grid">
        <!-- 최근 회원가입 -->
        <div class="grid-item grid-col-6">
            <div class="grid-item-header">
                <h3>최근 회원가입</h3>
                <a href="{{ route('backoffice.users.index') }}" class="more-btn">
                    <i class="fas fa-arrow-right"></i> 더보기
                </a>
            </div>
            <div class="grid-item-body">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>이름</th>
                            <th>이메일</th>
                            <th>가입일</th>
                            <th>상태</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>관리자</td>
                            <td>admin@example.com</td>
                            <td>{{ now()->subDays(1)->format('Y-m-d') }}</td>
                            <td><span class="table-badge badge-warning">미인증</span></td>
                            <td>
                                <a href="#" class="table-action table-action-edit">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 최근 게시글 -->
        <div class="grid-item grid-col-6">
            <div class="grid-item-header">
                <h3>최근 게시글</h3>
                <a href="{{ route('backoffice.posts.index') }}" class="more-btn">
                    <i class="fas fa-arrow-right"></i> 더보기
                </a>
            </div>
            <div class="grid-item-body">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>제목</th>
                            <th>게시판</th>
                            <th>작성자</th>
                            <th>등록일</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center">등록된 게시글이 없습니다.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 게시판 리스트 -->
        <div class="grid-item grid-col-6">
            <div class="grid-item-header">
                <h3>게시판 현황</h3>
                <a href="{{ route('backoffice.boards.index') }}" class="more-btn">
                    <i class="fas fa-arrow-right"></i> 더보기
                </a>
            </div>
            <div class="grid-item-body">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>게시판명</th>
                            <th>게시글</th>
                            <th>댓글</th>
                            <th>최근활동</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>자유게시판</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td><span class="table-badge badge-success">활성</span></td>
                        </tr>
                        <tr>
                            <td>공지사항</td>
                            <td>0</td>
                            <td>0</td>
                            <td>-</td>
                            <td><span class="table-badge badge-success">활성</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 최근 접속 로그 -->
        <div class="grid-item grid-col-6">
            <div class="grid-item-header">
                <h3>최근 접속 로그</h3>
                <a href="{{ route('backoffice.logs.access') }}" class="more-btn">
                    <i class="fas fa-arrow-right"></i> 더보기
                </a>
            </div>
            <div class="grid-item-body">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>IP</th>
                            <th>사용자</th>
                            <th>접속시간</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>192.168.1.101</td>
                            <td>관리자</td>
                            <td>{{ now()->subMinutes(5)->format('Y-m-d H:i') }}</td>
                            <td><span class="table-badge badge-success">성공</span></td>
                        </tr>
                        <tr>
                            <td>118.235.12.45</td>
                            <td>user@example.com</td>
                            <td>{{ now()->subHours(1)->format('Y-m-d H:i') }}</td>
                            <td><span class="table-badge badge-success">성공</span></td>
                        </tr>
                        <tr>
                            <td>121.143.88.201</td>
                            <td>unknown</td>
                            <td>{{ now()->subHours(2)->format('Y-m-d H:i') }}</td>
                            <td><span class="table-badge badge-danger">실패</span></td>
                        </tr>
                        <tr>
                            <td>58.124.56.102</td>
                            <td>member@example.com</td>
                            <td>{{ now()->subHours(3)->format('Y-m-d H:i') }}</td>
                            <td><span class="table-badge badge-success">성공</span></td>
                            </td>
                        </tr>
                        <tr>
                            <td>211.214.110.53</td>
                            <td>admin@example.com</td>
                            <td>{{ now()->subHours(5)->format('Y-m-d H:i') }}</td>
                            <td><span class="table-badge badge-success">성공</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 접속 통계 그래프 -->
    <div class="stats-chart-section">
        <div class="grid-item grid-col-12">
            <div class="grid-item-header">
                <h3>방문객 통계</h3>
                <div class="chart-controls">
                    <button class="chart-period-btn active" data-period="7">7일</button>
                    <button class="chart-period-btn" data-period="30">30일</button>
                </div>
            </div>
            <div class="grid-item-body">
                <div class="visitor-summary">
                    <div class="visitor-stat">
                        <span class="visitor-label">오늘 방문객</span>
                        <span class="visitor-number">127</span>
                    </div>
                    <div class="visitor-stat">
                        <span class="visitor-label">총 방문객</span>
                        <span class="visitor-number">15,847</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="visitorChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/backoffice/dashboard.js') }}"></script>
@endsection
