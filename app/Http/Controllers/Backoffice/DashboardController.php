<?php

namespace App\Http\Controllers\Backoffice;

use App\Models\User;
use App\Models\Board;
use App\Models\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    /**
     * 대시보드 메인 페이지
     */
    public function index()
    {
        $data = [
            'boards' => $this->getBoardsOrderedByMenu(),
            'totalBoards' => Board::where('is_single_page', false)->count(),
            'totalPosts' => $this->getTotalPostsCount(),
            'activeBanners' => $this->getActiveBannersCount(),
            'activePopups' => $this->getActivePopupsCount(),
            'visitorStats' => $this->getVisitorStats(),
        ];
        
        return $this->view('backoffice.dashboard', $data);
    }

    /**
     * 방문객 통계 API
     */
    public function statistics()
    {
        $today = now()->format('Y-m-d');
        
        // 오늘 방문객 수
        $todayVisitors = DB::table('daily_visitor_stats')
            ->where('visit_date', $today)
            ->value('visitor_count') ?? 0;
            
        // 총 방문객 수
        $totalVisitors = DB::table('daily_visitor_stats')
            ->sum('visitor_count');
            
        // 일별 통계 (이번 달)
        $dailyStats = $this->getDailyChartData();
        
        // 월별 통계 (이번 년도)
        $monthlyStats = $this->getMonthlyChartData();
        
        return response()->json([
            'today_visitors' => $todayVisitors,
            'total_visitors' => $totalVisitors,
            'daily_stats' => $dailyStats,
            'monthly_stats' => $monthlyStats,
        ]);
    }

    /**
     * 방문객 통계 데이터 가져오기
     */
    private function getVisitorStats()
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');
        
        return [
            'today_visitors' => DB::table('daily_visitor_stats')
                ->where('visit_date', $today)
                ->value('visitor_count') ?? 0,
            'total_visitors' => DB::table('daily_visitor_stats')
                ->sum('visitor_count'),
            'daily_stats' => $this->getDailyChartData(),
            'monthly_stats' => $this->getMonthlyChartData(),
        ];
    }

    /**
     * 방문객 차트 데이터 생성
     */
    /**
     * 일별 차트 데이터 생성 (이번 달)
     */
    private function getDailyChartData()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $stats = DB::table('daily_visitor_stats')
            ->whereBetween('visit_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->orderBy('visit_date')
            ->get();
            
        $labels = [];
        $data = [];
        
        // 이번 달의 모든 날짜에 대해 데이터 생성
        $current = $startOfMonth->copy();
        while ($current->lte($endOfMonth)) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('m/d');
            
            $stat = $stats->firstWhere('visit_date', $dateStr);
            $data[] = $stat ? $stat->visitor_count : 0;
            
            $current->addDay();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * 월별 차트 데이터 생성 (이번 년도)
     */
    private function getMonthlyChartData()
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();
        
        $stats = DB::table('daily_visitor_stats')
            ->whereBetween('visit_date', [$startOfYear->format('Y-m-d'), $endOfYear->format('Y-m-d')])
            ->get()
            ->groupBy(function($item) {
                return substr($item->visit_date, 0, 7); // YYYY-MM
            });
            
        $labels = [];
        $data = [];
        
        // 이번 년도의 모든 월에 대해 데이터 생성
        $current = $startOfYear->copy();
        while ($current->lte($endOfYear)) {
            $monthStr = $current->format('Y-m');
            $labels[] = $current->format('Y년 m월');
            
            $monthStats = $stats->get($monthStr, collect());
            $monthTotal = $monthStats->sum('visitor_count');
            $data[] = $monthTotal;
            
            $current->addMonth();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * 전체 게시글 수 계산 (실시간 집계)
     */
    private function getTotalPostsCount(): int
    {
        // 활성 게시판만 카운트 (단일페이지 제외)
        $boards = Board::where('is_active', true)
            ->where('is_single_page', false)
            ->get();
        $totalPosts = 0;
        
        foreach ($boards as $board) {
            $tableName = 'board_' . $board->slug;
            try {
                $count = DB::table($tableName)->count();
                $totalPosts += $count;
            } catch (\Exception $e) {
                // 테이블이 존재하지 않는 경우 무시
                continue;
            }
        }
        
        return $totalPosts;
    }


    /**
     * 활성 배너 수 계산
     */
    private function getActiveBannersCount(): int
    {
        try {
            return DB::table('banners')
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('start_date')
                          ->orWhere('start_date', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                })
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 활성 팝업 수 계산
     */
    private function getActivePopupsCount(): int
    {
        try {
            return DB::table('popups')
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('start_date')
                          ->orWhere('start_date', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                })
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * admin_menus의 1차 메뉴 순서대로 게시판 정렬
     */
    private function getBoardsOrderedByMenu()
    {
        try {
            // 1. 모든 게시판 가져오기
            $boards = Board::where('is_single_page', false)->get();
            
            // 2. 각 게시판의 메뉴 정보 확인
            $boardsWithMenu = $boards->map(function($board) {
                $adminMenu = $board->getAdminMenu();
                $board->adminMenu = $adminMenu;
                return $board;
            });
            
            // 3. 메뉴가 있는 게시판만 필터링
            $filteredBoards = $boardsWithMenu->filter(function($board) {
                return $board->adminMenu !== null;
            });
            
            // 4. 1차 메뉴의 order로 정렬
            $sortedBoards = $filteredBoards->sortBy(function($board) {
                return $board->adminMenu->parent->order;
            });
            
            // 5. 최대 10개만 반환
            return $sortedBoards->take(10)->values();
        } catch (\Exception $e) {
            dd('Error: ' . $e->getMessage());
        }
    }
}
