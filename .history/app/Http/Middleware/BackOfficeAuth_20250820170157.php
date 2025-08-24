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
        // 인증되지 않은 경우 로그인 페이지로 리디렉션
        if (!Auth::check()) {
            return redirect('/backoffice/login');
        }

        return $next($request);
    }
}
