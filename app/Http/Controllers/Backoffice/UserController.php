<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\Backoffice\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 회원 목록을 표시
     */
    public function index(Request $request)
    {
        $users = $this->userService->getUsersWithFilters($request);
        return view('backoffice.users.index', compact('users'));
    }

    /**
     * 회원 생성 폼 표시
     */
    public function create()
    {
        return view('backoffice.users.create');
    }

    /**
     * 회원 저장
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $this->userService->createUser($data);

        return redirect()->route('backoffice.users.index')
            ->with('success', '회원이 추가되었습니다.');
    }

    /**
     * 회원 상세 정보 표시
     */
    public function show(User $user)
    {
        return view('backoffice.users.show', compact('user'));
    }

    /**
     * 회원 수정 폼 표시
     */
    public function edit(User $user)
    {
        return view('backoffice.users.edit', compact('user'));
    }

    /**
     * 회원 정보 업데이트
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());
        
        return redirect()->route('backoffice.users.index')
            ->with('success', '회원 정보가 수정되었습니다.');
    }

    /**
     * 회원 삭제 처리
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        
        return redirect()->route('backoffice.users.index')
            ->with('success', '회원이 삭제되었습니다.');
    }
}
