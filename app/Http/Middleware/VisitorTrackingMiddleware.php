<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VisitorTrackingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 백오피스 페이지는 제외
        if (str_starts_with($request->path(), 'backoffice')) {
            return $next($request);
        }

        try {
            $this->trackVisitor($request);
        } catch (\Exception $e) {
            // 방문객 추적 실패해도 페이지는 정상 동작
            Log::warning('방문객 추적 실패: ' . $e->getMessage());
        }

        return $next($request);
    }

    /**
     * 방문객 정보 추적
     */
    private function trackVisitor(Request $request): void
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $pageUrl = $request->fullUrl();
        $referer = $request->header('referer');
        $sessionId = $request->session()->getId();
        
        // 오늘 날짜
        $today = now()->format('Y-m-d');
        
        // 같은 IP + User Agent로 오늘 이미 방문했는지 확인
        $isUnique = !DB::table('visitor_logs')
            ->where('ip_address', $ipAddress)
            ->where('user_agent', $userAgent)
            ->whereDate('created_at', $today)
            ->exists();

        // 방문 로그 저장
        DB::table('visitor_logs')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'page_url' => $pageUrl,
            'referer' => $referer,
            'session_id' => $sessionId,
            'is_unique' => $isUnique,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 일일 통계 업데이트
        $this->updateDailyStats($today, $isUnique);
    }

    /**
     * 일일 통계 업데이트
     */
    private function updateDailyStats(string $date, bool $isUnique): void
    {
        // 오늘 통계가 있는지 확인
        $todayStats = DB::table('daily_visitor_stats')
            ->where('visit_date', $date)
            ->first();

        if ($todayStats) {
            // 기존 통계 업데이트
            DB::table('daily_visitor_stats')
                ->where('visit_date', $date)
                ->update([
                    'visitor_count' => DB::raw('visitor_count + 1'),
                    'page_views' => DB::raw('page_views + 1'),
                    'unique_visitors' => $isUnique ? DB::raw('unique_visitors + 1') : DB::raw('unique_visitors'),
                    'updated_at' => now(),
                ]);
        } else {
            // 새 통계 생성
            DB::table('daily_visitor_stats')->insert([
                'visit_date' => $date,
                'visitor_count' => 1,
                'page_views' => 1,
                'unique_visitors' => $isUnique ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
