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
        // 세션에서 사용자 ID 직접 확인
        $session = $request->session();
        $sessionUserId = $session->get('user_id');
        
        // 디버깅: 인증 상태 확인
        $isAuthenticated = Auth::check();
        $user = Auth::user();
        
        \Log::info('BackOfficeAuth 미들웨어 실행', [
            'url' => $request->url(),
            'is_authenticated' => $isAuthenticated,
            'user_id' => $user ? $user->id : null,
            'session_user_id' => $sessionUserId,
            'session_id' => $session->getId(),
        ]);
        
        // 세션에 사용자 ID가 있으면 인증 상태 복원
        if ($sessionUserId && !$isAuthenticated) {
            $user = \App\Models\User::find($sessionUserId);
            if ($user) {
                Auth::login($user);
                $isAuthenticated = true;
                \Log::info('세션에서 사용자 인증 복원', ['user_id' => $user->id]);
            }
        }
        
        // 세션에 사용자 ID가 없지만 인증된 경우, 세션에 사용자 ID 저장
        if ($isAuthenticated && !$sessionUserId) {
            $session->put('user_id', $user->id);
            \Log::info('세션에 사용자 ID 저장', ['user_id' => $user->id]);
        }
        
        // 세션 유지를 위한 강제 조치
        if ($isAuthenticated) {
            $session->put('auth_user_id', $user->id);
            $session->put('auth_keep_alive', time());
            \Log::info('세션 유지 강제 설정', ['user_id' => $user->id]);
        }
        
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
