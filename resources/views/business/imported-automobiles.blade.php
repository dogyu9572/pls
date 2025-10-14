@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual imported_automobiles_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">수입자동차 종합물류 <br/>One-Stop PDI Service, <br/><strong>PLS</strong>가 만들어갑니다.</div>
			<p>(주)피엘에스는 국내 최초의 수입자동차 PDI <br class="mo_vw"/>전문기업으로, <br class="pc_vw"/>평택항 자유무역지역의 첨단 물류 <br class="mo_vw"/>인프라와 자체 개발한 PDI 운영관리 플랫폼을 <br class="mo_vw"/>기반으로 <br class="pc_vw"/>차량 진단부터 보관·운송까지 <br class="mo_vw"/>One-Stop PDI Service를 제공하고 있으며, <br/>고객 만족과 최고의 품질을 실현하기 위해 <br class="mo_vw"/>지속적으로 서비스 혁신을 추구하고 있습니다.</p>
		</div>
	</div>
	
	<div class="imported_automobiles01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Project Overview</p><strong>사업개요</strong></div>
				<div class="con">
					<p>PDI(Pre-Delivery Inspection)는 해외에서 생산된 자동차가 국내에 수입된 뒤 고객에게 인도되기 전에 수행하는 최종 품질 점검 공정입니다.<br/>
					차량의 안전성과 신뢰성을 확보하기 위해 기능 진단과 내·외관 점검은 물론, 고객 맞춤형 액세서리 옵션 장착까지 체계적인 <br class="pc_vw">
					PDI Process를 통해 차량의 완성도를 높이고 고객에게 믿을 수 있는 품질과 만족을 제공합니다.</p>
					<ul>
						<li class="i1"><strong>국내 최초 수입자동차 PDI 전문기업</strong><p>1993년 인천남항에서 국내 최초로 PDI사업을 도입하여 30년 이상 업계를 선도해 오고 있습니다.</p></li>
						<li class="i2"><strong>자동차물류 최적화 지역 기반</strong><p>평택항 항만배후단지의 뛰어난 지리적 이점과 자유무역지역의 특화된 물류 인프라를 기반으로, <br class="pc_vw">수입자동차에 특화된 효율적이고 전문적인 Logistics Service를 제공합니다.</p></li>
						<li class="i3"><strong>PDI Process Management Platform</strong><p>Know-how를 바탕으로 구축된 PDI 운영관리 플랫폼(PDI Process Management Platform)을 통해, <br class="pc_vw">PDI 전 과정을 체계적이고 효율적으로 관리하고 있습니다.</p></li>
						<li class="i4"><strong>PDI Total Logistics Solutions</strong><p>입항부터 하역, 통관, 보관, PDI 서비스, 운송에 이르기까지 수입자동차 물류 전 과정을 아우르는 <br class="pc_vw">전문적이고 효율적인 Logistics Solutions을 기반으로 One-Stop PDI Service로 제공합니다</p></li>
						<li class="i5"><strong>High-Capacity Storage</strong><p>자유무역지역 내 단일 최대 규모인 153,167.7㎡의 부지를 보유하고 있으며, <br class="pc_vw">평택항 인근의 다양한 형태의 보관 야적장과 주차타워를 통해 확장된 차량 Storage Service 를 제공합니다.</p></li>
						<li class="i6"><strong>PDI업계 최대 EV Charging Infra</strong><p>PDI 업계 최대 규모의 EV 충전 인프라를 선도적으로 구축하고 있으며, <br class="pc_vw">보관 중인 EV 차량에 대해 정기적인 관리를 통해 고객 맞춤형 Maintenance Service를 제공합니다.</p></li>
						<li class="i7"><strong>친환경 가치를 실현하는 지속가능한 Logistics Partner</strong><p>태양광 발전 및 사용용수 재활용 시스템 등 친환경 인프라를 바탕으로, <br class="pc_vw">고객사의 RE100 실현을 지원하는 지속가능 물류 파트너입니다.</p></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="imported_automobiles02 slide_arrow_type1 gbox sub_padding">
		<div class="inner">
			<div class="imported_automobiles02_slide slide_area">
				@for ($i = 1; $i <= 15; $i++)
				<div class="box"><span class="imgfit"><img src="{{ asset('images/img_imported_automobiles' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="image"></span><span class="txt"><p>{{ ['PDI 시설', 'PDI 시설', 'PDI 시설', 'PDI 시설', 'PDI 시설', 'PLS 시설', 'PDI Platform', 'PDI Platform', 'PDI 진단', '충전인프라', '충전인프라', '태양광+충전인프라', 'PLS HQ 전경', 'PLS SS 전경', 'Parking tower'][$i-1] }}</p></span></div>
				@endfor
			</div>
			<div class="navi navi_type1 flex_center">
				<div class="paging"></div>
				<button class="papl pause on">정지</button>
				<button class="papl play">재생</button>
			</div>
		</div>
	</div>

	<div class="imported_automobiles03 sub_padding">
		<div class="inner">
			<div class="stit mb"><p>PDI Main Process</p><strong>PDI 주요 Process</strong></div>
			<ul class="process_list set4">
				@for ($i = 1; $i <= 12; $i++)
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="image"></div><div class="txt"><span>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span><p>{{ ['Port Survey & Transport', 'Inspection / Storage', 'Maintenance', 'Fueling & Charging', 'Diagnosis', 'Washing', 'In/Exterior Check', 'Function Check', 'Accessory Install', 'Polishing / Cleaning', 'Final Inspection', 'Delivery'][$i-1] }}</p></div></li>
				@endfor
			</ul>
		</div>
	</div>

	<div class="imported_automobiles04 sub_padding gbox_b">
		<div class="inner">
			<div class="stit mb"><p>PDI Partner</p><strong>PDI 고객사</strong></div>
		</div>
		<div class="partner_list marquee_list">
			<div class="slide">
				@for($i = 0; $i < 3; $i++)
					@foreach($brands as $brand)
						@if($brand->thumbnail)
							<img src="{{ asset('storage/' . $brand->thumbnail) }}" alt="{{ $brand->title }}">
						@endif
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
