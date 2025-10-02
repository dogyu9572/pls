<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorLog;
use App\Models\DailyVisitorStat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrackVisitor
{
    /**
     * 방문자 추적 미들웨어
     * 사용자 페이지 접속 시 방문 로그를 기록하고 일일 통계를 업데이트
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // 백오피스, API 경로는 제외
        if (!$request->is('backoffice*') && !$request->is('api/*')) {
            try {
                $this->recordVisitor($request);
            } catch (\Exception $e) {
                // 방문자 로그 기록 실패 시에도 페이지는 정상 표시
                Log::error('방문자 로그 기록 실패: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * 방문자 정보 기록
     */
    private function recordVisitor(Request $request): void
    {
        $ipAddress = $request->ip();
        $sessionId = $request->hasSession() ? $request->session()->getId() : null;
        $now = now();
        $today = $now->format('Y-m-d');
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();
        
        // 고유 방문자 여부 확인 (IP 기반)
        $isUnique = !VisitorLog::where('ip_address', $ipAddress)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->exists();

        // 방문 로그 기록
        VisitorLog::create([
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'page_url' => $request->fullUrl(),
            'referer' => $request->header('referer'),
            'session_id' => $sessionId,
            'is_unique' => $isUnique,
        ]);

        // 일별 통계 업데이트
        \Log::info("방문자 로그 기록 완료", [
            'ip' => $ipAddress,
            'is_unique' => $isUnique,
            'today' => $today
        ]);
        $this->updateDailyStats($today, $isUnique);
    }

    /**
     * 일별 통계 업데이트
     */
    private function updateDailyStats(string $date, bool $isUnique): void
    {
        $stat = DailyVisitorStat::firstOrCreate(
            ['visit_date' => $date],
            [
                'visitor_count' => 0,
                'page_views' => 0,
            ]
        );

        // 단일 UPDATE 쿼리로 여러 컬럼 동시 증가
        $result = DB::table($stat->getTable())
            ->where('id', $stat->id)
            ->update([
                'page_views' => DB::raw('page_views + 1'),
                'visitor_count' => DB::raw('visitor_count + ' . ($isUnique ? 1 : 0)),
            ]);
            
        \Log::info("일별 통계 업데이트", [
            'date' => $date,
            'is_unique' => $isUnique,
            'stat_id' => $stat->id,
            'update_result' => $result
        ]);
    }
}