<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query();
        
        // 사용여부 필터
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // 게시기간 필터
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->where('use_period', true)
                  ->where('start_date', '>=', $request->start_date)
                  ->where('end_date', '<=', $request->end_date);
        }
        
        // 등록일 필터
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }
        
        // 배너제목 검색
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        
        // 목록 개수 설정
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        $banners = $query->ordered()->paginate($perPage);
        
        return view('backoffice.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backoffice.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'main_text' => 'nullable|string|max:255',
            'sub_text' => 'nullable|string|max:255',
            'url' => 'nullable|url',
            'url_target' => 'nullable|in:_self,_blank',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'use_period' => 'boolean',
            'is_active' => 'boolean',
            'desktop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        // 이미지 업로드 처리
        if ($request->hasFile('desktop_image')) {
            $data['desktop_image'] = $request->file('desktop_image')->store('banners', 'public');
        }
        
        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('backoffice.banners.index')
                        ->with('success', '배너가 성공적으로 생성되었습니다.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('backoffice.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('backoffice.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'main_text' => 'nullable|string|max:255',
            'sub_text' => 'nullable|string|max:255',
            'url' => 'nullable|url',
            'url_target' => 'nullable|in:_self,_blank',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'use_period' => 'boolean',
            'is_active' => 'boolean',
            'desktop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        // 이미지 제거 처리
        if ($request->input('remove_desktop_image') == '1') {
            if ($banner->desktop_image) {
                Storage::disk('public')->delete($banner->desktop_image);
            }
            $data['desktop_image'] = null;
        }
        
        if ($request->input('remove_mobile_image') == '1') {
            if ($banner->mobile_image) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $data['mobile_image'] = null;
        }
        
        // 이미지 업로드 처리
        if ($request->hasFile('desktop_image')) {
            // 기존 이미지 삭제
            if ($banner->desktop_image) {
                Storage::disk('public')->delete($banner->desktop_image);
            }
            $data['desktop_image'] = $request->file('desktop_image')->store('banners', 'public');
        }
        
        if ($request->hasFile('mobile_image')) {
            // 기존 이미지 삭제
            if ($banner->mobile_image) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('backoffice.banners.index')
                        ->with('success', '배너가 성공적으로 수정되었습니다.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        // 이미지 파일 삭제
        if ($banner->desktop_image) {
            Storage::disk('public')->delete($banner->desktop_image);
        }
        if ($banner->mobile_image) {
            Storage::disk('public')->delete($banner->mobile_image);
        }

        $banner->delete();

        return redirect()->route('backoffice.banners.index')
                        ->with('success', '배너가 성공적으로 삭제되었습니다.');
    }

    /**
     * 배너 순서 업데이트
     */
    public function updateOrder(Request $request)
    {
        $bannerOrder = $request->input('bannerOrder', []);

        foreach ($bannerOrder as $item) {
            if (isset($item['id']) && isset($item['order'])) {
                $banner = Banner::find($item['id']);
                if ($banner) {
                    $banner->sort_order = $item['order'];
                    $banner->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
