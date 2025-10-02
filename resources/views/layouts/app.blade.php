<!DOCTYPE html>
<html lang="ko">
<head>
<title>PLS Corp</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes">

<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon"/>
<link rel="stylesheet" href="{{ asset('css/font.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/reactive.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/popup.css') }}" media="all">

<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/com.js') }}"></script>
<script src="{{ asset('js/popup.js') }}"></script>

</head>
<body>
<div class="blind_link"><a href="#mainContent">본문 바로가기</a></div>

<div class="header {{ isset($gNum) && $gNum == 'main' ? 'main' : '' }}">
	<a href="/" class="logo flex_center"><img src="{{ asset('images/logo.svg') }}" alt="logo"><h1>PLS Corp</h1></a>
	<div class="gnb">
		<div class="menu {{ isset($gNum) && $gNum == '01' ? 'on' : '' }}"><a href="{{ route('information.ceo-message') }}">기업정보</a>
			<div class="snb">
				<a href="{{ route('information.ceo-message') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">CEO 인사말</a>
				<a href="{{ route('information.about-company') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">회사소개</a>
				<a href="{{ route('information.history') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">회사연혁</a>
				<a href="{{ route('information.quality-environmental') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '04' ? 'on' : '' }}">품질/환경경영</a>
				<a href="{{ route('information.safety-health') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '05' ? 'on' : '' }}">안전/보건경영</a>
				<a href="{{ route('information.ethical') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '06' ? 'on' : '' }}">윤리경영</a>
			</div>
		</div>
		<div class="menu {{ isset($gNum) && $gNum == '02' ? 'on' : '' }}"><a href="{{ route('business.imported-automobiles') }}">사업분야</a>
			<div class="snb">
				<a href="{{ route('business.imported-automobiles') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">수입자동차 PDI사업</a>
				<a href="{{ route('business.port-logistics') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">항만물류사업</a>
				<a href="{{ route('business.special-vehicle') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">특장차 제조사업</a>
			</div>
		</div>
		<div class="menu {{ isset($gNum) && $gNum == '03' ? 'on' : '' }}"><a href="{{ route('recruitment.ideal-employee') }}">인재채용</a>
			<div class="snb">
				<a href="{{ route('recruitment.ideal-employee') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">인재상</a>
				<a href="{{ route('recruitment.personnel') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">인사제도</a>
				<a href="{{ route('recruitment.welfare') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">복지제도</a>
				<a href="{{ route('recruitment.information') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '04' ? 'on' : '' }}">채용안내</a>
			</div>
		</div>
		<div class="menu {{ isset($gNum) && $gNum == '04' ? 'on' : '' }}"><a href="{{ route('pr-center.announcements') }}">홍보센터</a>
			<div class="snb">
				<a href="{{ route('pr-center.announcements') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">PLS 공지</a>
				<a href="{{ route('pr-center.news') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">PLS 소식</a>
				<a href="{{ route('pr-center.location') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">오시는 길</a>
			</div>
		</div>
	</div>
	<div class="right flex_center">
		<a href="{{ route('contact-us') }}" class="btn_contact">CONTACT US</a>
		<div class="langs">
			<a href="/" class="on">KOR</a>
			<a href="#this">ENG</a>
		</div>
	</div>
	<a href="javascript:void(0);" class="btn_menu">
		<p class="t"></p>
		<p class="m"></p>
		<p class="b"></p>
	</a>
	<div class="sitemap">
		<div class="img"></div>
		<div class="menus">
			<div class="menu {{ isset($gNum) && $gNum == '01' ? 'on' : '' }}">
				<button type="button">기업정보<i></i></button>
				<div class="snb">
					<a href="{{ route('information.ceo-message') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">CEO 인사말</a>
					<a href="{{ route('information.about-company') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">회사소개</a>
					<a href="{{ route('information.history') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">회사연혁</a>
					<a href="{{ route('information.quality-environmental') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '04' ? 'on' : '' }}">품질/환경경영</a>
					<a href="{{ route('information.safety-health') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '05' ? 'on' : '' }}">안전/보건경영</a>
					<a href="{{ route('information.ethical') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '06' ? 'on' : '' }}">윤리경영</a>
				</div>
			</div>
			<div class="menu {{ isset($gNum) && $gNum == '02' ? 'on' : '' }}">
				<button type="button">사업분야<i></i></button>
				<div class="snb">
					<a href="{{ route('business.imported-automobiles') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">수입자동차 PDI사업</a>
					<a href="{{ route('business.port-logistics') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">항만물류사업</a>
					<a href="{{ route('business.special-vehicle') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">특장차 제조사업</a>
				</div>
			</div>
			<div class="menu {{ isset($gNum) && $gNum == '03' ? 'on' : '' }}">
				<button type="button">인재채용<i></i></button>
				<div class="snb">
					<a href="{{ route('recruitment.ideal-employee') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">인재상</a>
					<a href="{{ route('recruitment.personnel') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">인사제도</a>
					<a href="{{ route('recruitment.welfare') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">복지제도</a>
					<a href="{{ route('recruitment.information') }}" class="{{ isset($gNum) && $gNum == '03' && isset($sNum) && $sNum == '04' ? 'on' : '' }}">채용안내</a>
				</div>
			</div>
			<div class="menu {{ isset($gNum) && $gNum == '04' ? 'on' : '' }}">
				<button type="button">홍보센터<i></i></button>
				<div class="snb">
					<a href="{{ route('pr-center.announcements') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">PLS 공지</a>
					<a href="{{ route('pr-center.news') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">PLS 소식</a>
					<a href="{{ route('pr-center.location') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">오시는 길</a>
				</div>
			</div>
		</div>
	</div>
</div>

@if(isset($gNum) && $gNum !== 'main' && $gNum !== '00')
<div class="sub_tit">
	<div class="inner">
		<div class="title">{{ $gName ?? '' }}</div>
		<p>고객과 함께 내일을 꿈꾸며, 새로운 삶의 가치를 창조한다.</p>
		<div class="aside">
			<dl>
				<dt><button type="button">{{ $sName ?? '' }}</button></dt>
				<dd>
				@if($gNum == '01')
					<a href="{{ route('information.ceo-message') }}" class="{{ $gNum == '01' && $sNum == '01' ? 'on' : '' }}">CEO 인사말</a>
					<a href="{{ route('information.about-company') }}" class="{{ $gNum == '01' && $sNum == '02' ? 'on' : '' }}">회사소개</a>
					<a href="{{ route('information.history') }}" class="{{ $gNum == '01' && $sNum == '03' ? 'on' : '' }}">회사연혁</a>
					<a href="{{ route('information.quality-environmental') }}" class="{{ $gNum == '01' && $sNum == '04' ? 'on' : '' }}">품질/환경경영</a>
					<a href="{{ route('information.safety-health') }}" class="{{ $gNum == '01' && $sNum == '05' ? 'on' : '' }}">안전/보건경영</a>
					<a href="{{ route('information.ethical') }}" class="{{ $gNum == '01' && $sNum == '06' ? 'on' : '' }}">윤리경영</a>
				@elseif($gNum == '02')
					<a href="{{ route('business.imported-automobiles') }}" class="{{ $gNum == '02' && $sNum == '01' ? 'on' : '' }}">수입자동차 PDI사업</a>
					<a href="{{ route('business.port-logistics') }}" class="{{ $gNum == '02' && $sNum == '02' ? 'on' : '' }}">항만물류사업</a>
					<a href="{{ route('business.special-vehicle') }}" class="{{ $gNum == '02' && $sNum == '03' ? 'on' : '' }}">특장차 제조사업</a>
				@elseif($gNum == '03')
					<a href="{{ route('recruitment.ideal-employee') }}" class="{{ $gNum == '03' && $sNum == '01' ? 'on' : '' }}">인재상</a>
					<a href="{{ route('recruitment.personnel') }}" class="{{ $gNum == '03' && $sNum == '02' ? 'on' : '' }}">인사제도</a>
					<a href="{{ route('recruitment.welfare') }}" class="{{ $gNum == '03' && $sNum == '03' ? 'on' : '' }}">복지제도</a>
					<a href="{{ route('recruitment.information') }}" class="{{ $gNum == '03' && $sNum == '04' ? 'on' : '' }}">채용안내</a>
				@elseif($gNum == '04')
					<a href="{{ route('pr-center.announcements') }}" class="{{ $gNum == '04' && $sNum == '01' ? 'on' : '' }}">PLS 공지</a>
					<a href="{{ route('pr-center.news') }}" class="{{ $gNum == '04' && $sNum == '02' ? 'on' : '' }}">PLS 소식</a>
					<a href="{{ route('pr-center.location') }}" class="{{ $gNum == '04' && $sNum == '03' ? 'on' : '' }}">오시는 길</a>
				@endif
				</dd>
			</dl>
		</div>
	</div>
</div>
@endif

@yield('content')

<div class="footer">
	<div class="point" id="unfixed"></div>
	<button type="button" class="gotop">TOP</button>
	<div class="inner">
		<div class="flogo"><img src="{{ asset('images/logo.svg') }}" alt="logo"></div>
		<ul class="links">
			<li><a href="{{ route('terms.privacy-policy') }}"><strong>개인정보처리방침</strong></a></li>
			<li><a href="{{ route('terms.email') }}">이메일 무단수집 거부</a></li>
		</ul>
		<ul class="address">
			<li><strong>주소</strong>경기도 평택시 포승읍 서동대로 437-100</li>
			<li><strong>대표 전화</strong>031)684-9661~5</li>
		</ul>
		<p class="copy">Copyrightⓒ2012 by PLS. All right Reserved.</p>
		<dl class="family">
			<dt><button type="button">FAMILY SITE</button></dt>
			<dd>
				@foreach($familySites as $site)
					@php
						$customFields = $site->custom_fields;
						if (is_string($customFields)) {
							$customFields = json_decode($customFields, true) ?: [];
						}
						$siteName = $customFields['kor'] ?? '';
						$siteUrl = $customFields['url'] ?? '#';
					@endphp
					<a href="{{ $siteUrl }}" target="_blank">{{ $siteName }}</a>
				@endforeach
			</dd>
		</dl>
	</div>
</div>

@if(isset($popups) && $popups->count() > 0 && request()->routeIs('home'))
<!-- 팝업 표시 -->
@foreach($popups as $popup)
    @if($popup->popup_display_type === 'normal')
        <!-- 일반팝업 (새창) -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 일반팝업은 새창으로 열기
                const popupUrl = '{{ route("popup.show", $popup->id) }}';
                // 관리자에서 설정한 위치 사용
                const popupWidth = {{ $popup->width }};
                const popupHeight = {{ $popup->height }};
                const popupTop = {{ $popup->position_top ?? 100 }};
                const popupLeft = {{ $popup->position_left ?? 100 }};
                
                const popupFeatures = 'width=' + popupWidth + ',height=' + popupHeight + ',left=' + popupLeft + ',top=' + popupTop + ',scrollbars=yes,resizable=yes,menubar=no,toolbar=no,location=no,status=no';
                
                // 쿠키 확인 함수
                function getCookie(name) {
                    const value = '; ' + document.cookie;
                    const parts = value.split('; ' + name + '=');
                    if (parts.length === 2) {
                        return parts.pop().split(';').shift();
                    }
                    return null;
                }
                
                // 쿠키 확인 후 팝업 열기
                const cookieName = 'popup_hide_{{ $popup->id }}';
                if (!getCookie(cookieName)) {
                    window.open(popupUrl, 'popup_{{ $popup->id }}', popupFeatures);
                }
            });
        </script>
    @else
        <!-- 레이어팝업 (오버레이) -->
        @php
            $cookieName = 'popup_hide_' . $popup->id;
            $isHidden = isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] === '1';
        @endphp
        <div class="popup-layer popup-fixed" 
             id="popup-{{ $popup->id }}"
             data-popup-id="{{ $popup->id }}"
             data-display-type="layer"
             style="position: absolute !important; width: {{ $popup->width }}px; height: auto; top: {{ $popup->position_top }}px; left: {{ $popup->position_left }}px; z-index: 99999; {{ $isHidden ? 'display: none;' : '' }}">
            
            
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
                <label class="popup-today-label" data-popup-id="{{ $popup->id }}">
                    <input type="checkbox" class="popup-today-close" data-popup-id="{{ $popup->id }}">
                    1일 동안 보지 않음
                </label>
                <button type="button" class="popup-footer-close-btn" data-popup-id="{{ $popup->id }}">닫기</button>
            </div>
        </div>
    @endif
@endforeach
@endif

<!-- 팝업 JavaScript -->
<script src="{{ asset('js/popup.js') }}"></script>

</body>
</html>