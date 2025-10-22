@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual imported_automobiles_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">Imported Vehicle Comprehensive Logistics <br/>One-Stop PDI Service, <br/><strong>PLS</strong> makes it happen.</div>
			<p>(주)피엘에스 is Korea's first imported vehicle PDI specialized company. Based on the advanced logistics infrastructure of Pyeongtaek Port Free Trade Zone and our self-developed PDI operation management platform, we provide One-Stop PDI Service from vehicle diagnosis to storage and transportation, and continuously pursue service innovation to realize customer satisfaction and the highest quality.</p>
		</div>
	</div>
	
	<div class="imported_automobiles01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Project Overview</p><strong>Business Overview</strong></div>
				<div class="con">
					<p>PDI (Pre-Delivery Inspection) is the final quality inspection process performed after vehicles produced overseas are imported domestically and before being delivered to customers.<br/>
					Through a systematic PDI Process, we ensure vehicle safety and reliability through functional diagnosis and interior/exterior inspection, as well as customer-customized accessory option installation, to enhance vehicle completeness and provide customers with reliable quality and satisfaction.</p>
					<ul>
						<li class="i1"><strong>Korea's First Imported Vehicle PDI Specialized Company</strong><p>Since introducing PDI business for the first time in Korea at Incheon South Port in 1993, we have been leading the industry for over 30 years.</p></li>
						<li class="i2"><strong>Automotive Logistics Optimized Regional Base</strong><p>Based on the excellent geographical advantages of Pyeongtaek Port's port hinterland and the specialized logistics infrastructure of the Free Trade Zone, we provide efficient and professional Logistics Service specialized for imported vehicles.</p></li>
						<li class="i3"><strong>PDI Process Management Platform</strong><p>Through the PDI operation management platform (PDI Process Management Platform) built on know-how, we systematically and efficiently manage the entire PDI process.</p></li>
						<li class="i4"><strong>PDI Total Logistics Solutions</strong><p>We provide One-Stop PDI Service based on professional and efficient Logistics Solutions covering the entire process of imported vehicle logistics from port entry to unloading, customs clearance, storage, PDI service, and transportation.</p></li>
						<li class="i5"><strong>High-Capacity Storage</strong><p>We own the largest single site of 153,167.7㎡ within the Free Trade Zone, and provide expanded vehicle Storage Service through various forms of storage yards and parking towers near Pyeongtaek Port.</p></li>
						<li class="i6"><strong>PDI Industry's Largest EV Charging Infrastructure</strong><p>We are leading the construction of the largest EV charging infrastructure in the PDI industry, and provide customer-customized Maintenance Service through regular management of stored EV vehicles.</p></li>
						<li class="i7"><strong>Sustainable Logistics Partner Realizing Eco-Friendly Values</strong><p>Based on eco-friendly infrastructure such as solar power generation and water recycling systems, we are a sustainable logistics partner supporting our customers' RE100 realization.</p></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="imported_automobiles02 slide_arrow_type1 gbox sub_padding">
		<div class="inner">
			<div class="imported_automobiles02_slide slide_area">
				@for ($i = 1; $i <= 15; $i++)
				<div class="box"><span class="imgfit"><img src="{{ asset('images/img_imported_automobiles' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="image"></span><span class="txt"><p>{{ ['PDI Facility', 'PDI Facility', 'PDI Facility', 'PDI Facility', 'PDI Facility', 'PLS Facility', 'PDI Platform', 'PDI Platform', 'PDI Diagnosis', 'Charging Infrastructure', 'Charging Infrastructure', 'Solar Power + Charging Infrastructure', 'PLS HQ View', 'PLS SS View', 'Parking Tower'][$i-1] }}</p></span></div>
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
			<div class="stit mb"><p>PDI Main Process</p><strong>PDI Main Process</strong></div>
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
			<div class="stit mb"><p>Business Contact</p><strong>사업문의</strong></div>
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
