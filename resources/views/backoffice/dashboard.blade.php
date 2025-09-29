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
        <div class="stat-card stat-boards">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3>활성 게시판</h3>
                <p class="stat-number">{{ $totalBoards }}</p>
            </div>
        </div>

        <div class="stat-card stat-posts">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3>총 게시글</h3>
                <p class="stat-number">{{ $totalPosts ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card stat-banners">
            <div class="stat-icon">
                <i class="fas fa-image"></i>
            </div>
            <div class="stat-info">
                <h3>활성 배너</h3>
                <p class="stat-number">{{ $activeBanners ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card stat-popups">
            <div class="stat-icon">
                <i class="fas fa-window-restore"></i>
            </div>
            <div class="stat-info">
                <h3>활성 팝업</h3>
                <p class="stat-number">{{ $activePopups ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- 데이터 그리드 -->
    <div class="dashboard-grid">
        <!-- 게시판 현황 -->
        <div class="grid-item grid-col-12">
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
                            <th>최근활동</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boards as $board)
                            <tr>
                                <td>
                                    <a href="{{ route('backoffice.board-posts.index', ['slug' => $board->slug]) }}" 
                                       class="text-decoration-none text-dark fw-medium">
                                        {{ $board->name }}
                                    </a>
                                </td>
                                <td>{{ $board->getPostsCount() }}</td>
                                <td>{{ $board->updated_at ? $board->updated_at->diffForHumans() : '-' }}</td>
                                <td>
                                    <span class="table-badge badge-{{ $board->is_active ? 'success' : 'secondary' }}">
                                        {{ $board->is_active ? '활성' : '비활성' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">등록된 게시판이 없습니다.</td>
                            </tr>
                        @endforelse
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
                    <button class="chart-type-btn active" data-type="daily">일별</button>
                    <button class="chart-type-btn" data-type="monthly">월별</button>
                </div>
            </div>
            <div class="grid-item-body">
                <div class="visitor-summary">
                    <div class="visitor-stat">
                        <span class="visitor-label">오늘 방문객</span>
                        <span class="visitor-number">{{ $visitorStats['today_visitors'] ?? 0 }}</span>
                    </div>
                    <div class="visitor-stat">
                        <span class="visitor-label">총 방문객</span>
                        <span class="visitor-number">{{ number_format($visitorStats['total_visitors'] ?? 0) }}</span>
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
