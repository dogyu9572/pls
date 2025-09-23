<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use Illuminate\Http\Request;

class AdminMenuController extends BaseController
{
    /**
     * 메뉴 목록 표시
     */
    public function index()
    {
        $menus = AdminMenu::getMenuTree();
        return $this->view('backoffice.menus.index', compact('menus'));
    }

    /**
     * 메뉴 생성 폼 표시
     */
    public function create()
    {
        $menus = AdminMenu::whereNull('parent_id')->orderBy('order')->get();
        return $this->view('backoffice.menus.create', compact('menus'));
    }

    /**
     * 새 메뉴 저장
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:admin_menus,id',
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // is_active가 제출되지 않았으면 false로 설정
        $validated['is_active'] = $request->has('is_active');

        AdminMenu::create($validated);

        return redirect()->route('backoffice.admin-menus.index')
            ->with('success', '메뉴가 성공적으로 생성되었습니다.');
    }

    /**
     * 메뉴 수정 폼 표시
     */
    public function edit(AdminMenu $menu)
    {
        $menus = AdminMenu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('order')
            ->get();

        return $this->view('backoffice.menus.edit', compact('menu', 'menus'));
    }

    /**
     * 메뉴 업데이트
     */
    public function update(Request $request, AdminMenu $menu)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:admin_menus,id',
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // is_active가 제출되지 않았으면 false로 설정
        $validated['is_active'] = $request->has('is_active');

        $menu->update($validated);

        return redirect()->route('backoffice.admin-menus.index')
            ->with('success', '메뉴가 성공적으로 수정되었습니다.');
    }

    /**
     * 메뉴 삭제
     */
    public function destroy(AdminMenu $menu)
    {
        $menu->delete();

        return redirect()->route('backoffice.admin-menus.index')
            ->with('success', '메뉴가 성공적으로 삭제되었습니다.');
    }

    /**
     * 메뉴 순서 업데이트
     */
    public function updateOrder(Request $request)
    {
        $menuOrder = $request->input('menuOrder', []);

        foreach ($menuOrder as $item) {
            if (isset($item['id']) && isset($item['order'])) {
                $menu = AdminMenu::find($item['id']);
                if ($menu) {
                    $menu->order = $item['order'];
                    $menu->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * 메뉴 부모 업데이트 (드래그로 메뉴 이동)
     */
    public function updateParent(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:admin_menus,id',
            'parent_id' => 'nullable|exists:admin_menus,id'
        ]);

        try {
            $menu = AdminMenu::find($validated['menu_id']);
            $menu->parent_id = $validated['parent_id'];
            $menu->save();

            return response()->json([
                'success' => true,
                'message' => '메뉴가 성공적으로 이동되었습니다.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '메뉴 이동 중 오류가 발생했습니다: ' . $e->getMessage()
            ], 500);
        }
    }

}
