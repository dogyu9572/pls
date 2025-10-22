<!DOCTYPE html>
<html lang="ko">
<head>
<title>PLS Corp</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes">

<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon"/>
<link rel="stylesheet" href="{{ asset('css/font.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/styles_eng.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/reactive.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/reactive_eng.css') }}" media="all">
<link rel="stylesheet" href="{{ asset('css/popup.css') }}" media="all">
@yield('additional_styles')

<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/com.js') }}"></script>
<script src="{{ asset('js/popup.js') }}"></script>

</head>
<body>
<div class="blind_link"><a href="#mainContent">Skip to content</a></div>

<div class="header {{ isset($gNum) && $gNum == 'main' ? 'main' : '' }}">
	<a href="/eng" class="logo flex_center"><img src="{{ asset('images/logo.svg') }}" alt="logo"><h1>PLS Corp</h1></a>
	<div class="gnb">
		<div class="menu {{ isset($gNum) && $gNum == '01' ? 'on' : '' }}"><a href="{{ route('eng.information.ceo-message') }}">Company Information</a>
			<div class="snb">
				<a href="{{ route('eng.information.ceo-message') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">CEO's Message</a>
				<a href="{{ route('eng.information.about-company') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">About Us</a>
				<a href="{{ route('eng.information.history') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">PLS History</a>
				<a href="{{ route('eng.information.quality-environmental') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '04' ? 'on' : '' }}">Quality & Environmental Management</a>
				<a href="{{ route('eng.information.safety-health') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '05' ? 'on' : '' }}">Safety & Health Management</a>
				<a href="{{ route('eng.information.ethical') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '06' ? 'on' : '' }}">Ethical Management</a>
			</div>
		</div>
		<div class="menu {{ isset($gNum) && $gNum == '02' ? 'on' : '' }}"><a href="{{ route('eng.business.imported-automobiles') }}">Business Areas</a>
			<div class="snb">
				<a href="{{ route('eng.business.imported-automobiles') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">Imported Vehicle PDI Business</a>
				<a href="{{ route('eng.business.port-logistics') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">Port Logistics Business</a>
				<a href="{{ route('eng.business.special-vehicle') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">Special Vehicle Manufacturing Business</a>
			</div>
		</div>
		<div class="menu {{ isset($gNum) && $gNum == '04' ? 'on' : '' }}"><a href="{{ route('eng.pr-center.announcements') }}">PR Center</a>
			<div class="snb">
				<a href="{{ route('eng.pr-center.announcements') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">PLS Announcements</a>
				<a href="{{ route('eng.pr-center.news') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">PLS News</a>
				<a href="{{ route('eng.pr-center.location') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">Location & Directions</a>
			</div>
		</div>
	</div>
	<div class="right flex_center">
		<a href="{{ route('eng.contact-us') }}" class="btn_contact">CONTACT US</a>
		<div class="langs">
			<a href="/">KOR</a>
			<a href="/eng/" class="on">ENG</a>
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
				<button type="button">Company Information<i></i></button>
				<div class="snb">
					<a href="{{ route('eng.information.ceo-message') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">CEO's Message</a>
					<a href="{{ route('eng.information.about-company') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">About Us</a>
					<a href="{{ route('eng.information.history') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">PLS History</a>
					<a href="{{ route('eng.information.quality-environmental') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '04' ? 'on' : '' }}">Quality & Environmental Management</a>
					<a href="{{ route('eng.information.safety-health') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '05' ? 'on' : '' }}">Safety & Health Management</a>
					<a href="{{ route('eng.information.ethical') }}" class="{{ isset($gNum) && $gNum == '01' && isset($sNum) && $sNum == '06' ? 'on' : '' }}">Ethical Management</a>
				</div>
			</div>
			<div class="menu {{ isset($gNum) && $gNum == '02' ? 'on' : '' }}">
				<button type="button">Business Areas<i></i></button>
				<div class="snb">
					<a href="{{ route('eng.business.imported-automobiles') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">Imported Vehicle PDI Business</a>
					<a href="{{ route('eng.business.port-logistics') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">Port Logistics Business</a>
					<a href="{{ route('eng.business.special-vehicle') }}" class="{{ isset($gNum) && $gNum == '02' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">Special Vehicle Manufacturing Business</a>
				</div>
			</div>
			<div class="menu {{ isset($gNum) && $gNum == '04' ? 'on' : '' }}">
				<button type="button">PR Center<i></i></button>
				<div class="snb">
					<a href="{{ route('eng.pr-center.announcements') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '01' ? 'on' : '' }}">PLS Announcements</a>
					<a href="{{ route('eng.pr-center.news') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '02' ? 'on' : '' }}">PLS News</a>
					<a href="{{ route('eng.pr-center.location') }}" class="{{ isset($gNum) && $gNum == '04' && isset($sNum) && $sNum == '03' ? 'on' : '' }}">Location & Directions</a>
				</div>
			</div>
		</div>
	</div>
</div>

@if(isset($gNum) && $gNum !== 'main' && $gNum !== '00')
<div class="sub_tit">
	<div class="inner">
		<div class="title">{{ $gName ?? '' }}</div>
		<p>Creating new values for life, together with our customers for a better tomorrow.</p>
		<div class="aside">
			<dl>
				<dt><button type="button">{{ $sName ?? '' }}</button></dt>
				<dd>
				@if($gNum == '01')
					<a href="{{ route('eng.information.ceo-message') }}" class="{{ $gNum == '01' && $sNum == '01' ? 'on' : '' }}">CEO's Message</a>
					<a href="{{ route('eng.information.about-company') }}" class="{{ $gNum == '01' && $sNum == '02' ? 'on' : '' }}">About Us</a>
					<a href="{{ route('eng.information.history') }}" class="{{ $gNum == '01' && $sNum == '03' ? 'on' : '' }}">PLS History</a>
					<a href="{{ route('eng.information.quality-environmental') }}" class="{{ $gNum == '01' && $sNum == '04' ? 'on' : '' }}">Quality & Environmental Management</a>
					<a href="{{ route('eng.information.safety-health') }}" class="{{ $gNum == '01' && $sNum == '05' ? 'on' : '' }}">Safety & Health Management</a>
					<a href="{{ route('eng.information.ethical') }}" class="{{ $gNum == '01' && $sNum == '06' ? 'on' : '' }}">Ethical Management</a>
				@elseif($gNum == '02')
					<a href="{{ route('eng.business.imported-automobiles') }}" class="{{ $gNum == '02' && $sNum == '01' ? 'on' : '' }}">Imported Vehicle PDI Business</a>
					<a href="{{ route('eng.business.port-logistics') }}" class="{{ $gNum == '02' && $sNum == '02' ? 'on' : '' }}">Port Logistics Business</a>
					<a href="{{ route('eng.business.special-vehicle') }}" class="{{ $gNum == '02' && $sNum == '03' ? 'on' : '' }}">Special Vehicle Manufacturing Business</a>
				@elseif($gNum == '04')
					<a href="{{ route('eng.pr-center.announcements') }}" class="{{ $gNum == '04' && $sNum == '01' ? 'on' : '' }}">PLS Announcements</a>
					<a href="{{ route('eng.pr-center.news') }}" class="{{ $gNum == '04' && $sNum == '02' ? 'on' : '' }}">PLS News</a>
					<a href="{{ route('eng.pr-center.location') }}" class="{{ $gNum == '04' && $sNum == '03' ? 'on' : '' }}">Location & Directions</a>
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
			<li><a href="{{ route('eng.terms.privacy-policy') }}"><strong>Privacy Policy</strong></a></li>
			<li><a href="{{ route('eng.terms.email-rejection') }}">Email Collection Refusal</a></li>
		</ul>
		<ul class="address">
			<li><strong>Address</strong>437-100 Seodong-daero, Poseung-eup, Pyeongtaek-si, Gyeonggi-do, Republic of Korea</li>
			<li><strong>Tel</strong>+82-31-684-9661~5</li>
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
                    Not seen for 1 day
                </label>
                <button type="button" class="popup-footer-close-btn" data-popup-id="{{ $popup->id }}">Close</button>
            </div>
        </div>
    @endif
@endforeach
@endif

<!-- 팝업 JavaScript -->
<script src="{{ asset('js/popup.js') }}"></script>
@yield('additional_scripts')

</body>
</html>