<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    /**
     * 세션 연장 처리
     */
    public function extend(Request $request): JsonResponse
    {
        try {
            // 현재 사용자 인증 확인
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => '인증되지 않은 사용자입니다.',
                ], 401);
            }

            // 세션을 갱신하여 만료 시간을 연장
            $request->session()->regenerate();
            
            // 세션에 새로운 로그인 시간 저장
            $request->session()->put('login_time', now()->timestamp);
            
            // 세션 연장 성공 플래그 설정
            $request->session()->put('session_extended', true);
            
            return response()->json([
                'success' => true,
                'message' => '세션이 성공적으로 연장되었습니다.',
                'timestamp' => now()->toISOString(),
                'new_login_time' => now()->timestamp
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '세션 연장 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}