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
        $menus = AdminMenu::whereNull('parent_id')->get();
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

        return redirect()->route('backoffice.menus.index')
            ->with('success', '메뉴가 성공적으로 생성되었습니다.');
    }

    /**
     * 메뉴 수정 폼 표시
     */
    public function edit(AdminMenu $menu)
    {
        $menus = AdminMenu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->get();

        return view('backoffice.menus.edit', compact('menu', 'menus'));
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

        // 메뉴가 자기 자신을 부모로 선택하는 것 방지
        if ($validated['parent_id'] == $menu->id) {
            return redirect()->back()->withErrors(['parent_id' => '메뉴는 자기 자신을 부모로 선택할 수 없습니다.']);
        }

        // is_active가 제출되지 않았으면 false로 설정
        $validated['is_active'] = $request->has('is_active');

        $menu->update($validated);

        return redirect()->route('backoffice.menus.index')
            ->with('success', '메뉴가 성공적으로 수정되었습니다.');
    }

    /**
     * 메뉴 삭제
     */
    public function destroy(AdminMenu $menu)
    {
        $menu->delete();

        return redirect()->route('backoffice.menus.index')
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
}
