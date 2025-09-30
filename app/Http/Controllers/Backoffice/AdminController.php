<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Services\Backoffice\AdminService;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * 관리자 목록을 표시
     */
    public function index(Request $request)
    {
        $admins = $this->adminService->getAdminsWithFilters($request);

        return view('backoffice.admins.index', compact('admins'));
    }

    /**
     * 관리자 생성 폼 표시
     */
    public function create()
    {
        $menus = $this->adminService->getAllMenus();
        return view('backoffice.admins.create', compact('menus'));
    }

    /**
     * 관리자 저장
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $admin = $this->adminService->createAdmin($data);
        
        // 권한 저장
        if (!empty($permissions)) {
            $this->adminService->saveMenuPermissions($admin->id, $permissions);
        }

        return redirect()->route('backoffice.admins.index')
            ->with('success', '관리자가 추가되었습니다.');
    }

    /**
     * 관리자 상세 정보 표시
     */
    public function show($id)
    {
        $admin = $this->adminService->getAdmin($id);
        return view('backoffice.admins.show', compact('admin'));
    }

    /**
     * 관리자 수정 폼 표시
     */
    public function edit($id)
    {
        $admin = $this->adminService->getAdmin($id);
        $menus = $this->adminService->getAllMenus();
        return view('backoffice.admins.edit', compact('admin', 'menus'));
    }

    /**
     * 관리자 정보 업데이트
     */
    public function update(UpdateAdminRequest $request, $id)
    {
        $admin = $this->adminService->getAdmin($id);
        $data = $request->validated();
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $this->adminService->updateAdmin($admin, $data);
        
        // 권한 업데이트
        $this->adminService->saveMenuPermissions($admin->id, $permissions);

        return redirect()->route('backoffice.admins.index')
            ->with('success', '관리자 정보가 수정되었습니다.');
    }

    /**
     * 관리자 삭제
     */
    public function destroy($id)
    {
        $admin = $this->adminService->getAdmin($id);
        $this->adminService->deleteAdmin($admin);

        return redirect()->route('backoffice.admins.index')
            ->with('success', '관리자가 삭제되었습니다.');
    }
}
