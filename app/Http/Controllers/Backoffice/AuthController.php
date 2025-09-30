<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 로그인 폼 표시
     */
    public function showLoginForm()
    {
        return view('backoffice.login');
    }

    /**
     * 로그인 처리
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => 'required|string',
            'password' => 'required',
        ]);

        // login_id로 사용자 찾기
        $user = \App\Models\User::where('login_id', $credentials['login_id'])
                                ->where('is_active', true)
                                ->first();

        if (!$user) {
            return back()->withErrors([
                'login_id' => '존재하지 않는 로그인 ID입니다.',
            ])->withInput();
        }

        // 비밀번호 확인
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'login_id' => '로그인 ID 또는 비밀번호가 일치하지 않습니다.',
            ])->withInput();
        }

        // 관리자 권한 확인
        if (!$user->isAdmin()) {
            return back()->withErrors([
                'login_id' => '백오피스 접근 권한이 없습니다.',
            ])->withInput();
        }

        // 로그인 성공
        \Illuminate\Support\Facades\Auth::login($user);
        
        // 마지막 로그인 시간 업데이트
        $user->update(['last_login_at' => now()]);
        
        // 세션에 로그인 시간 저장 (세션 타이머용)
        $request->session()->put('login_time', now()->timestamp);
        
        // localStorage 초기화를 위한 플래그 설정 (프론트엔드에서 감지)
        $request->session()->put('session_reset', true);
        
        $request->session()->regenerate();
        
        return redirect()->intended('/backoffice');
    }

    /**
     * 로그아웃 처리
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/backoffice/login');
    }
}
