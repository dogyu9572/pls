<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\Backoffice\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    /**
     * 사용자 인증 미들웨어 적용
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    /**
     * 사용자 프로필 화면 표시
     */
    public function profile()
    {
        return view('users.profile', [
            'user' => $this->userService->getCurrentUser()
        ]);
    }

    /**
     * 사용자 프로필 업데이트
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userService->getCurrentUser();
        
        $this->userService->updateProfile($user, $request->validated());

        return redirect()->route('profile')->with('status', '프로필이 성공적으로 업데이트되었습니다.');
    }
}
