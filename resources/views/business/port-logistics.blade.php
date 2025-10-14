@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual port_logistics_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">항만 물류의 새로운 가치 <br/><strong>Value Chain</strong> 확대</div>
			<p>(주)피엘에스는 평택항을 거점으로 <br class="mo_vw"/>항만물류사업(하역·보관·운송)을 수행하고 있으며, <br/>안정적인 항만운영 역량을 기반으로 화물의 다변화 및 <br/>사업영역 확장을 통하여 성장을 도모하고 있습니다.</p>
		</div>
	</div>
	
	<div class="port_logistics01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Project Overview</p><strong>사업개요</strong></div>
				<div class="con">
					<p>GS그룹 발전사에 납품되는 Bio-mass 발전 연료의 안정적인 공급망 구축을 위해, 해외에서 국내로 수입되는 화물의 항만 하역부터 <br class="pc_vw"/>
					보관, 관리, 그리고 발전소까지의 최종 운송에 이르는 전 과정을 아우르는 Total Logistics Service를 제공하고 있습니다.<br/>
					이를 통해 발전 연료 물류 과정의 효율성과 신뢰성을 확보하고, 고객사의 안정적인 에너지 생산 활동을 지원하고 있습니다.</p>
					<div class="imgs">
						<img src="{{ asset('images/img_port_logistics01_01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics01_02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics01_03.jpg') }}" alt="image">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="port_logistics02 sub_padding gbox">
		<div class="inner">
			<div class="stit mb"><p>Cargo handled in port logistics</p><strong>항만물류 취급화물</strong></div>
			<ul>
				<li class="i1">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_psk01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_psk02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_psk03.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">PKS(Palm Kernel Shel)</div><p>팜오일 추출 과정에서 발생하는 단단한 껍질로, <br class="pc_vw"/>발열량 <strong>3,500~4,200 kcal/kg</strong>의 안정적인 <br class="pc_vw"/>에너지원입니다. 낮은 수분 함량과 견과류 껍질과 유사한 <br class="pc_vw"/>섬유질 구조로 친환경 바이오매스 연료에 적합합니다.</p>
					</div>
				</li>
				<li class="i2">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_woodpellet01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodpellet02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodpellet03.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">Wood Pellet</div><p>목재 부산물을 압축해 만든 친환경 바이오매스 연료로, <br class="pc_vw"/>발열량 <strong>4,000~4,500 kcal/kg</strong>의 높은 에너지 효율과 <br class="pc_vw"/>낮은 탄소 배출이 특징입니다. 발전소, 산업용 보일러, <br class="pc_vw"/>난방 연료 등 다양한 분야에서 활용됩니다.</p>
					</div>
				</li>
				<li class="i3">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_woodchip01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodchip03.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodchip02.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">Wood Chip</div><p>목재를 잘게 절단·파쇄한 바이오매스 연료로, <br class="pc_vw"/>발열량 <strong>2,000~3,000 kcal/kg</strong>의 경제적인 <br class="pc_vw"/>에너지원입니다.원료 확보가 용이하고 가격 경쟁력이 <br class="pc_vw"/>뛰어나 발전소 및 산업용 보일러에서 널리 사용됩니다.</p>
					</div>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="port_logistics03 sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Port Logistics Process</p><strong>항만물류 Process</strong></div>
			<ul class="process_list set3">
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b01.jpg') }}" alt="image"></div><div class="txt"><span>01</span><p>연료화물 선적<em>북미/베트남/인도네시아</em></p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b02.jpg') }}" alt="image"></div><div class="txt"><span>02</span><p>평택/당진항 입항</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b03.jpg') }}" alt="image"></div><div class="txt"><span>03</span><p>하역 작업</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b04.jpg') }}" alt="image"></div><div class="txt"><span>04</span><p>옥내보관</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b05.jpg') }}" alt="image"></div><div class="txt"><span>05</span><p>운송</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b06.jpg') }}" alt="image"></div><div class="txt"><span>06</span><p>발전소 납품(출고)</p></div></li>
			</ul>
		</div>
	</div>

	<div class="imported_automobiles04 sub_padding gbox_b">
		<div class="inner">
			<div class="stit mb"><p>Port Logistics Partner</p><strong>항만물류 업무 협력사</strong></div>
		</div>
		<div class="partner_list marquee_list">
			<div class="slide">
				@for($i = 0; $i < 3; $i++)
					@foreach($partners as $partner)
						@if($partner->thumbnail)
							<img src="{{ asset('storage/' . $partner->thumbnail) }}" alt="{{ $partner->title }}">
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
				<li class="i1"><div class="tt">TEL</div><p><strong>{{ $logisticsTel }}</strong></p></li>
				<li class="i2"><div class="tt">MAIL</div><p><strong>{{ $logisticsMail }}</strong></p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->

<link rel="stylesheet" href="{{ asset('css/slick.css') }}" media="all">
<script src="{{ asset('js/slick.js') }}"></script>
<script>
//<![CDATA[
//port_logistics02_slide
	$(".port_logistics02_slide").slick({
		arrows: false,
		dots: false,
		autoplay: true,
		autoplaySpeed: 3000,
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
