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

<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/com.js') }}"></script>

</head>
<body>
<div class="blind_link"><a href="#mainContent">본문 바로가기</a></div>

<div class="header main">
	<a href="/" class="logo flex_center"><img src="{{ asset('images/logo.svg') }}" alt="logo"><h1>PLS Corp</h1></a>
	<div class="gnb">
		<div class="menu"><a href="/information/ceo_message.php">기업정보</a>
			<div class="snb">
				<a href="/information/ceo_message.php">CEO 인사말</a>
				<a href="/information/about_company.php">회사소개</a>
				<a href="/information/history.php">회사연혁</a>
			</div>
		</div>
		<div class="menu"><a href="/business/imported_automobiles.php">사업분야</a>
			<div class="snb">
				<a href="/business/imported_automobiles.php">수입자동차 PDI사업</a>
				<a href="/business/port_logistics.php">항만물류사업</a>
				<a href="/business/special_vehicle.php">특장차 제조사업</a>
			</div>
		</div>
		<div class="menu"><a href="/recruitment/ideal_employee.php">인재채용</a>
			<div class="snb">
				<a href="/recruitment/ideal_employee.php">인재상</a>
				<a href="/recruitment/personnel.php">인사제도</a>
				<a href="/recruitment/welfare.php">복지제도</a>
			</div>
		</div>
		<div class="menu"><a href="/pr_center/announcements.php">홍보센터</a>
			<div class="snb">
				<a href="/pr_center/announcements.php">PLS 공지</a>
				<a href="/pr_center/news.php">PLS 소식</a>
				<a href="/pr_center/location.php">오시는 길</a>
			</div>
		</div>
	</div>
	<div class="right flex_center">
		<a href="/contact_us/" class="btn_contact">CONTACT US</a>
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
</div>

@yield('content')

<div class="footer">
	<div class="point" id="unfixed"></div>
	<button type="button" class="gotop">TOP</button>
	<div class="inner">
		<div class="flogo"><img src="{{ asset('images/logo.svg') }}" alt="logo"></div>
		<ul class="links">
			<li><a href="/terms/privacy_policy.php"><strong>개인정보처리방침</strong></a></li>
			<li><a href="/terms/email.php">이메일 무단수집 거부</a></li>
		</ul>
		<ul class="address">
			<li><strong>주소</strong>경기도 평택시 포승읍 서동대로 437-100</li>
			<li><strong>대표 전화</strong>031)684-9661~5</li>
		</ul>
		<p class="copy">Copyrightⓒ2012 by PLS. All right Reserved.</p>
		<dl class="family">
			<dt><button type="button">FAMILY SITE</button></dt>
			<dd>
				<a href="#this">FAMILY SITE</a>
			</dd>
		</dl>
	</div>
</div>

</body>
</html>