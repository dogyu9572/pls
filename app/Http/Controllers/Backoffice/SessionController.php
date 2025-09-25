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
            // 세션을 갱신하여 만료 시간을 연장
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => '세션이 성공적으로 연장되었습니다.',
                'timestamp' => now()->toISOString()
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