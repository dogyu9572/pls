<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackOfficeAuth
{
    /**
     * 백오피스 인증 미들웨어
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 디버깅: 인증 상태 확인
        $isAuthenticated = Auth::check();
        $user = Auth::user();
        
        \Log::info('BackOfficeAuth 미들웨어 실행', [
            'url' => $request->url(),
            'is_authenticated' => $isAuthenticated,
            'user_id' => $user ? $user->id : null,
            'session_id' => $request->session()->getId(),
        ]);
        
        // 인증되지 않은 경우 로그인 페이지로 리디렉션
        if (!$isAuthenticated) {
            \Log::warning('인증되지 않은 사용자 접근', [
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);
            return redirect('/backoffice/login');
        }

        return $next($request);
    }
}
