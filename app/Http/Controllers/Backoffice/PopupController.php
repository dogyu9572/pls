<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    /**
     * 팝업 목록
     */
    public function index(Request $request)
    {
        $query = Popup::query();
        
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
        
        // 팝업제목 검색
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        
        // 팝업타입 필터
        if ($request->filled('popup_type')) {
            $query->where('popup_type', $request->popup_type);
        }
        
        // 팝업표시타입 필터
        if ($request->filled('popup_display_type')) {
            $query->where('popup_display_type', $request->popup_display_type);
        }
        
        // 목록 개수 설정
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        $popups = $query->ordered()->paginate($perPage);
        
        return view('backoffice.popups.index', compact('popups'));
    }

    /**
     * 팝업 생성 폼
     */
    public function create()
    {
        return view('backoffice.popups.create');
    }

    /**
     * 팝업 저장
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'use_period' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'width' => 'nullable|numeric|min:100|max:2000',
            'height' => 'nullable|numeric|min:100|max:2000',
            'position_top' => 'nullable|numeric|min:0',
            'position_left' => 'nullable|numeric|min:0',
            'url' => 'nullable|url',
            'url_target' => 'nullable|in:_self,_blank',
            'popup_type' => 'nullable|in:image,html',
            'popup_display_type' => 'nullable|in:normal,layer',
            'popup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'popup_content' => 'nullable|string',
            'remove_popup_image' => 'nullable|boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        // 이미지 업로드 처리
        if ($request->hasFile('popup_image')) {
            $data['popup_image'] = $request->file('popup_image')->store('popups', 'public');
        }
        
        // 기본값 설정
        $data['width'] = $data['width'] ?: 400;
        $data['height'] = $data['height'] ?: 300;
        $data['position_top'] = $data['position_top'] ?: 100;
        $data['position_left'] = $data['position_left'] ?: 100;
        
        // 게시기간 사용하지 않는 경우 날짜 초기화
        if (!$data['use_period']) {
            $data['start_date'] = null;
            $data['end_date'] = null;
        }

        Popup::create($data);

        return redirect()->route('backoffice.popups.index')
            ->with('success', '팝업이 성공적으로 생성되었습니다.');
    }

    /**
     * 팝업 상세
     */
    public function show(Popup $popup)
    {
        return view('backoffice.popups.show', compact('popup'));
    }

    /**
     * 팝업 수정 폼
     */
    public function edit(Popup $popup)
    {
        return view('backoffice.popups.edit', compact('popup'));
    }

    /**
     * 팝업 업데이트
     */
    public function update(Request $request, Popup $popup)
    {
        \Log::info('팝업 수정 요청 시작', ['popup_id' => $popup->id, 'request_data' => $request->all()]);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'use_period' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'width' => 'nullable|numeric|min:100|max:2000',
            'height' => 'nullable|numeric|min:100|max:2000',
            'position_top' => 'nullable|numeric|min:0',
            'position_left' => 'nullable|numeric|min:0',
            'url' => 'nullable|url',
            'url_target' => 'nullable|in:_self,_blank',
            'popup_type' => 'nullable|in:image,html',
            'popup_display_type' => 'nullable|in:normal,layer',
            'popup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'popup_content' => 'nullable|string',
            'remove_popup_image' => 'nullable|boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        // 이미지 업로드 처리
        if ($request->hasFile('popup_image')) {
            // 기존 이미지 삭제
            if ($popup->popup_image) {
                Storage::disk('public')->delete($popup->popup_image);
            }
            $data['popup_image'] = $request->file('popup_image')->store('popups', 'public');
        }
        
        // 이미지 제거 처리
        \Log::info('remove_popup_image 값:', ['value' => $request->remove_popup_image, 'has' => $request->has('remove_popup_image')]);
        if ($request->has('remove_popup_image') && $request->remove_popup_image) {
            \Log::info('이미지 제거 처리 시작');
            if ($popup->popup_image) {
                Storage::disk('public')->delete($popup->popup_image);
                \Log::info('기존 이미지 파일 삭제됨:', ['path' => $popup->popup_image]);
            }
            $data['popup_image'] = null;
            \Log::info('popup_image를 null로 설정');
        }
        
        // 기본값 설정
        $data['width'] = $data['width'] ?: 400;
        $data['height'] = $data['height'] ?: 300;
        $data['position_top'] = $data['position_top'] ?: 100;
        $data['position_left'] = $data['position_left'] ?: 100;
        
        // 게시기간 사용하지 않는 경우 날짜 초기화
        if (!$data['use_period']) {
            $data['start_date'] = null;
            $data['end_date'] = null;
        }

        $popup->update($data);

        return redirect()->route('backoffice.popups.index')
            ->with('success', '팝업이 성공적으로 수정되었습니다.');
    }

    /**
     * 팝업 삭제
     */
    public function destroy(Popup $popup)
    {
        // 이미지 삭제
        if ($popup->popup_image) {
            Storage::disk('public')->delete($popup->popup_image);
        }
        
        $popup->delete();

        return redirect()->route('backoffice.popups.index')
            ->with('success', '팝업이 성공적으로 삭제되었습니다.');
    }

    /**
     * 팝업 순서 업데이트
     */
    public function updateOrder(Request $request)
    {
        $popupOrder = $request->input('popupOrder', []);

        foreach ($popupOrder as $item) {
            if (isset($item['id']) && isset($item['order'])) {
                $popup = Popup::find($item['id']);
                if ($popup) {
                    $popup->sort_order = $item['order'];
                    $popup->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }
}