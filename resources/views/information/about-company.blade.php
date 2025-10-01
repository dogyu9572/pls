@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="about_company_top">
		<div class="inner">
			<div class="tit"><strong><span class="roboto">㈜</span>피엘에스</strong>는 <br class="mo_vw"/>국내 최초의 <strong>수입자동차 <br class="mo_vw"/>PDI 전문기업</strong>으로,<br/>축적된 물류 역량을 바탕으로 국내수입차 물류 선도기업으로 성장하고 있습니다.</div>
			<p>수입자동차 PDI, 항만 물류, 특장차 제조 등 <br class="mo_vw"/>다양한 사업을 수행하며, 평택항 자유무역지역을 <br class="mo_vw"/>거점으로 <br class="pc_vw"/>30여년간 축적된 물류 전문성과 <br class="mo_vw"/>운영 노하우로 최상의 물류 솔루션을 제공합니다.</p>
		</div>
		<div class="about_company_top_slide marquee_list">
			<div class="slide">
				<img src="{{ asset('images/about_company_top01.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top02.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top03.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top04.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top05.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top06.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top07.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top08.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top09.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top10.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top11.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top12.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top13.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top14.jpg') }}" alt="image">
				<img src="{{ asset('images/about_company_top15.jpg') }}" alt="image">
			</div>
		</div>
	</div>

	<div class="about_company_btm">
		<ul class="inner">
			<li class="i1"><div class="tit">설립연도</div><div class="con"><p><strong>2009</strong>년<strong>12</strong>월</p></div></li>
			<li class="i2"><div class="tit">주요사업</div><div class="con"><p>수입자동차 PDI사업, 항만물류사업, 특장차 제조사업</p></div></li>
			<li class="i3"><div class="tit">주주구성</div><div class="con"><p>GS글로벌<strong>90%</strong>日유라해운<strong>10%</strong></p></div></li>
			<li class="i4"><div class="tit">사업장</div><div class="con"><p><b>PLS HQ</b>경기도 평택시 포승읍 서동대로 437-100 <br class="hide"><span class="roboto">㈜</span>피엘에스<br/><b>PLS Storage Site</b>경기도 평택시 포승읍신영리 979-3</p></div></li>
		</ul>
	</div>

</div> <!-- //container -->

<script>
$(function() {
	const $wrap = $('.marquee_list');
	const $slide = $wrap.find('.slide');
	let speed = 100;
	$slide.append($slide.html());
	let singleWidth = 0;
	let pos = 0;
	let lastTime = null;
	let paused = false;
	function calcWidths() {
		const total = $slide[0].scrollWidth;
		singleWidth = total / 2;
	}
	$(window).on('load resize', function() {
		calcWidths();
	});
	$wrap.on('mouseenter', function() { paused = true; })
	   .on('mouseleave', function() { paused = false; });
	function step(timestamp) {
		if (!lastTime) lastTime = timestamp;
		const dt = (timestamp - lastTime) / 1000;
		lastTime = timestamp;

		if (!paused && singleWidth > 0) {
			pos -= speed * dt;
			if (Math.abs(pos) >= singleWidth) {
				pos += singleWidth;
			}
			$slide.css('transform', 'translateX(' + Math.round(pos) + 'px)');
		}
		requestAnimationFrame(step);
	}
	calcWidths();
	requestAnimationFrame(step);
});
</script>
@endsection
