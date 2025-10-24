@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="about_company_top">
		<div class="inner">
			<div class="tit"><strong>PLS</strong> Co., Ltd. is <br class="mo_vw"/>Korea's first <strong>specialized <br class="mo_vw"/>PDI (Pre-Delivery Inspection) company</strong> for imported vehicles, and has grown into a leading logistics provider in the imported car industry based on our accumulated logistics expertise.</div>
			<p>Engaged in diverse business areas including imported vehicle PDI, port logistics, and special vehicle manufacturing PLS delivers optimal logistics solutions from its base in the Pyeongtaek Port Free Trade Zone, leveraging over 30 years of professional experience and operational know-how in logistics.</p>
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
			<li class="i1"><div class="tit">Established</div><div class="con"><p><strong>December</strong> <strong>2009</strong></p></div></li>
			<li class="i2"><div class="tit">Main Business Areas</div><div class="con"><p>Imported Vehicle PDI Business, Port Logistics Business, Special Vehicle Manufacturing</p></div></li>
			<li class="i3"><div class="tit">Shareholders</div><div class="con"><p>GS Global<strong>90%</strong> Yura Shipping Co., Japan<strong>10%</strong></p></div></li>
			<li class="i4"><div class="tit">Business Locations</div><div class="con"><p><b>PLS HQs</b>437-100 Seodong-daero, Poseung-eup, Pyeongtaek-si, Gyeonggi-do, Republic of Korea <br/><b>PLS Storage Site</b>979-3, Sinyeong-ri, Poseung-eup, Pyeongtaek-si, Gyeonggi-do, Republic of Korea</p></div></li>
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
