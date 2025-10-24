@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual imported_automobiles_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">Comprehensive Logistics for Imported Vehicles <strong>PLS</strong> Delivers One-Stop PDI Services</div>
			<p>PLS Co., Ltd., Korea’s first specialized PDI (Pre-Delivery Inspection) company for imported vehicles, provides <strong>One-Stop PDI Services</strong> from vehicle inspection and diagnostics to storage and transportation based on the advanced logistics infrastructure of the <strong>Pyeongtaek Port Free Trade Zone</strong> and our self-developed <strong>PDI operation management platform</strong>.<br/>
				Through continuous service innovation, PLS strives to achieve <strong>customer satisfaction</strong> and <strong>the highest level of quality</strong>, leading the way in comprehensive logistics for imported vehicles.
			</p>
		</div>
	</div>
	
	<div class="imported_automobiles01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Project Overview</p><strong>Business Overview</strong></div>
				<div class="con">
					<p><strong>PDI (Pre-Delivery Inspection)</strong> is the final quality inspection process performed on imported vehicles before they are delivered to customers after being manufactured overseas.<br/>
					<br/>
					To ensure vehicle safety and reliability, PLS conducts comprehensive PDI procedures including functional diagnostics, interior and exterior inspections, and the installation of customer-specific accessory options.<br/>
					Through this systematic <strong>PDI process</strong>, we enhance vehicle completeness and deliver trusted quality and satisfaction to our customers.</p>
					<ul>
						<li class="i1"><strong>Korea’s First Specialized Imported Vehicle PDI Company</strong><p>Since introducing the nation’s first PDI business in Incheon South Port in 1993, PLS has been leading the industry for over 30 years as Korea’s pioneer in imported vehicle PDI services.</p></li>
						<li class="i2"><strong>Optimized Automotive Logistics Location</strong><p>Leveraging the excellent geographical advantages of the Pyeongtaek Port Hinterland Complex and the advanced logistics infrastructure within the Free Trade Zone, PLS provides efficient and specialized logistics services tailored for imported vehicles.</p></li>
						<li class="i3"><strong>PDI Process Management Platform</strong><p>Through the <b>PDI Process Management Platform</b> built on decades of operational know-how, PLS systematically and efficiently manages the entire PDI process — from vehicle arrival to final inspection and delivery.</p></li>
						<li class="i4"><strong>Total Logistics Solutions for Imported Vehicles</strong><p>From vessel unloading, customs clearance, storage, and PDI services to inland transportation, PLS offers <b>One-Stop PDI Services</b> based on integrated and professional <b>Total Logistics Solutions</b> for imported vehicles.</p></li>
						<li class="i5"><strong>High-Capacity Storage Facilities</strong><p>PLS owns a <b>153,167.7㎡</b> site within the Free Trade Zone the largest single-area facility in the industry and provides expanded vehicle storage services through various outdoor yards and multi-level parking towers near Pyeongtaek Port.</p></li>
						<li class="i6"><strong>Industry-Leading EV Charging Infrastructure</strong><p>As a leader in the PDI industry’s largest <b>EV charging infrastructure</b>, PLS provides regular maintenance and customized management services for electric vehicles stored at its facilities.</p></li>
						<li class="i7"><strong>Sustainable Logistics Partner for a Greener Future</strong><p>By adopting eco-friendly infrastructure such as <b>solar power generation</b> and <b>water recycling systems</b>, <br class="pc_vw"/>PLS supports clients’ <b>RE100 initiatives</b> and fulfills its role as a sustainable logistics partner that embodies environmental responsibility.</p></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="imported_automobiles02 slide_arrow_type1 gbox sub_padding">
		<div class="inner">
			<div class="imported_automobiles02_slide slide_area">
				@for ($i = 1; $i <= 15; $i++)
				<div class="box"><span class="imgfit"><img src="{{ asset('images/img_imported_automobiles' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="image"></span><span class="txt"><p>{{ ['PDI Facility', 'PDI Facility', 'PDI Facility', 'PDI Facility', 'PDI Facility', 'PLS Facility', 'PDI Platform', 'PDI Platform', 'PDI Inspection & Diagnostics', 'EV Charging Infrastructure', 'EV Charging Infrastructure', 'Solar Power & EV Charging Infrastructure', 'PLS HQ View', 'PLS SS View', 'Parking Tower'][$i-1] }}</p></span></div>
				@endfor
			</div>
			<div class="navi navi_type1 flex_center">
				<div class="paging"></div>
				<button class="papl pause on">Pause</button>
				<button class="papl play">Play</button>
			</div>
		</div>
	</div>

	<div class="imported_automobiles03 sub_padding">
		<div class="inner">
			<div class="stit mb"><p>PDI Main Process</p><strong>Main PDI Process</strong></div>
			<ul class="process_list set4">
				@for ($i = 1; $i <= 12; $i++)
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="image"></div><div class="txt"><span>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span><p>{{ ['Port Survey & Transport', 'Inspection / Storage', 'Maintenance', 'Fueling & Charging', 'Diagnosis', 'Washing', 'In/Exterior Check', 'Function Check', 'Accessory Install', 'Polishing / Cleaning', 'Final Inspection', 'Delivery'][$i-1] }}</p></div></li>
				@endfor
			</ul>
		</div>
	</div>

	<div class="imported_automobiles04 sub_padding gbox_b">
		<div class="inner">
			<div class="stit mb"><p>PDI Partner</p><strong>PDI Partners</strong></div>
		</div>
		<div class="partner_list marquee_list">
			<div class="slide">
				@for($i = 0; $i < 3; $i++)
					@foreach($brands as $brand)
						<img src="{{ $brand->thumbnail ? asset('storage/' . $brand->thumbnail) : asset('images/default.jpg') }}" alt="{{ $brand->title }}">
					@endforeach
				@endfor
			</div>
		</div>
	</div>

	<div class="business_contact sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Business Contact</p><strong>Business Contact</strong></div>
			<ul>
				<li class="i1"><div class="tt">TEL</div><p><strong>{{ $pdiTel }}</strong></p></li>
				<li class="i2"><div class="tt">MAIL</div><p><strong>{{ $pdiMail }}</strong></p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->

<link rel="stylesheet" href="{{ asset('css/slick.css') }}" media="all">
<script src="{{ asset('js/slick.js') }}"></script>
<script>
//<![CDATA[
//imported_automobiles02_slide
	$(".imported_automobiles02_slide").slick({
		arrows: true,
		dots: true,
		autoplay: true,
		autoplaySpeed: 5000,
		slidesToShow: 3,
		slidesToScroll: 3,
		appendDots: '.imported_automobiles02 .paging',
		responsive: [
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
				}
			},
		]
	});
	$('.imported_automobiles02 .play').click(function(){
		$('.imported_automobiles02_slide').slick('slickPlay');
		$(this).removeClass("on").siblings(".papl").addClass("on");
	}); 
	$('.imported_automobiles02 .pause').click(function(){
		$('.imported_automobiles02_slide').slick('slickPause');
		$(this).removeClass("on").siblings(".papl").addClass("on");
	});
//marquee
	$(function() {
		const $wrap = $('.partner_list');
		const $slide = $wrap.find('.slide');
		let speed = 100;
		let singleWidth = 0;
		let pos = 0;
		let lastTime = null;
		let paused = false;

		function calcWidths() {
			// 전체 너비의 1/3을 계산 (데이터가 3번 복제되어 있으므로)
			const total = $slide[0].scrollWidth;
			singleWidth = total / 3;
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
				// 1/3 지점에 도달하면 처음으로 리셋
				if (Math.abs(pos) >= singleWidth) {
					pos = 0;
				}
				$slide.css('transform', 'translateX(' + Math.round(pos) + 'px)');
			}

			requestAnimationFrame(step);
		}

		calcWidths();
		requestAnimationFrame(step);
	});
//]]>
</script>
@endsection
