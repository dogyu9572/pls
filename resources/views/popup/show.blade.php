<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $popup->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            padding: 0;
            margin: 0;
        }
        
        .popup-container {
            width: 100%;
            height: 100vh;
            margin: 0;
            background: #fff;
            border-radius: 0;
            box-shadow: none;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .popup-body {
            flex: 1;
            padding: 0;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .popup-body img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        
        .popup-body a {
            display: block;
            line-height: 0;
        }
        
        .popup-footer {
            background: #f8f9fa;
            padding: 10px 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .popup-today-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #666;
            user-select: none;
        }
        
        .popup-today-close {
            margin: 0;
        }
        
        .popup-close-btn {
            background: #6c757d;
            color: white;
            border: 1px solid #6c757d;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        
        .popup-close-btn:hover {
            background: #5a6268;
            border-color: #5a6268;
        }
        
        @media (max-width: 768px) {
            .popup-footer {
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="popup-container">
        <div class="popup-body">
            @if($popup->popup_type === 'image' && $popup->popup_image)
                @if($popup->url)
                    <a href="{{ $popup->url }}" target="{{ $popup->url_target }}">
                        <img src="{{ asset('storage/' . $popup->popup_image) }}" alt="{{ $popup->title }}">
                    </a>
                @else
                    <img src="{{ asset('storage/' . $popup->popup_image) }}" alt="{{ $popup->title }}">
                @endif
            @elseif($popup->popup_type === 'html' && $popup->popup_content)
                {!! $popup->popup_content !!}
            @endif
        </div>
        
        <div class="popup-footer">
            <label class="popup-today-label">
                <input type="checkbox" class="popup-today-close" id="todayClose">
                1일 동안 보지 않음
            </label>
            <button type="button" class="popup-close-btn" onclick="closePopup()">닫기</button>
        </div>
    </div>

    <script>
        function closePopup() {
            // 오늘 하루 보지 않기 체크 확인
            const todayClose = document.getElementById('todayClose').checked;
            if (todayClose) {
                // 쿠키 설정 (자정까지)
                const expires = new Date();
                expires.setHours(23, 59, 59, 999);
                document.cookie = 'popup_hide_{{ $popup->id }}=true; expires=' + expires.toUTCString() + '; path=/';
            }
            
            // 팝업 창 닫기
            window.close();
        }
        
        // ESC 키로 팝업 닫기
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopup();
            }
        });
    </script>
</body>
</html>
