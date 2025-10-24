@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual port_logistics_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">Expanding the <br/><strong>Value Chain</strong> in Port Logistics</div>
			<p>PLS Co., Ltd. operates port logistics services including <strong>stevedoring, storage, and transportation based</strong> at <strong>Pyeongtaek Port</strong>.<br/>
			Building on our strong and stable port operation capabilities, we are expanding our <strong>cargo portfolio</strong> and <strong>business areas</strong>, continuously enhancing the value chain and driving sustainable growth in the port logistics sector.</p>
		</div>
	</div>
	
	<div class="port_logistics01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Project Overview</p><strong>Business Overview</strong></div>
				<div class="con">
					<p>To establish a stable supply chain for biomass fuel supplied to GS Group’s power generation subsidiaries, PLS provides a <strong>Total Logistics Service</strong> that covers the entire process from <strong>port unloading and storage</strong> of imported cargo to <strong>management and final transportation</strong> to the power plants.<br/>
					Through this integrated logistics system, PLS ensures both <strong>efficiency and reliability</strong> in the fuel logistics process, supporting our clients’ <strong>stable and sustainable energy production operations.</strong></p>
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
			<div class="stit mb"><p>Cargo handled in port logistics</p><strong>Handled Cargo in Port Logistics</strong></div>
			<ul>
				<li class="i1">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_psk01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_psk02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_psk03.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">PKS(Palm Kernel Shel)</div><p>Palm Kernel Shells are hard shells generated during the palm oil extraction process, serving as a stable biomass fuel with a calorific value of <strong>3,500–4,200 kcal/kg</strong>.<br/>
						With low moisture content and a fibrous structure similar to nut shells, PKS is highly suitable as an <strong>eco-friendly renewable energy source</strong>.</p>
					</div>
				</li>
				<li class="i2">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_woodpellet01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodpellet02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodpellet03.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">Wood Pellet</div><p>Wood pellets are <strong>compressed biomass fuel</strong> made from wood by-products, featuring a high calorific value of <strong>4,000–4,500 kcal/kg</strong>, excellent energy efficiency, and low carbon emissions.<br/>
						They are widely used in <strong>power generation, industrial boilers, and heating systems</strong>, contributing to sustainable and clean energy production.</p>
					</div>
				</li>
				<li class="i3">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_woodchip01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodchip03.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodchip02.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">Wood Chip</div><p>Wood chips are <strong>biomass fuel</strong> produced by cutting and shredding wood materials, offering a calorific value of <strong>2,000–3,000 kcal/kg</strong> as a <strong>cost-effective energy source</strong>.<br/>
						Due to their easy availability and strong price competitiveness, wood chips are extensively used in <strong>power plants and industrial boilers</strong>.</p>
					</div>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="port_logistics03 sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Port Logistics Process</p><strong>Port Logistics Process</strong></div>
			<ul class="process_list set3">
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b01.jpg') }}" alt="image"></div><div class="txt"><span>01</span><p>Fuel Cargo Shipment<em>(North America / Vietnam / Indonesia)</em></p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b02.jpg') }}" alt="image"></div><div class="txt"><span>02</span><p>Arrival at Pyeongtaek & Dangjin Ports</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b03.jpg') }}" alt="image"></div><div class="txt"><span>03</span><p>Stevedoring (Unloading Operations)</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b04.jpg') }}" alt="image"></div><div class="txt"><span>04</span><p>Indoor Storage</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b05.jpg') }}" alt="image"></div><div class="txt"><span>05</span><p>Transportation</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b06.jpg') }}" alt="image"></div><div class="txt"><span>06</span><p>Delivery to Power Plants (Outbound)</p></div></li>
			</ul>
		</div>
	</div>

	<div class="imported_automobiles04 sub_padding gbox_b">
		<div class="inner">
			<div class="stit mb"><p>Port Logistics Partner</p><strong>Port Logistics Partners</strong></div>
		</div>
		<div class="partner_list marquee_list">
			<div class="slide">
				@for($i = 0; $i < 3; $i++)
					@foreach($partners as $partner)
						<img src="{{ $partner->thumbnail ? asset('storage/' . $partner->thumbnail) : asset('images/default.jpg') }}" alt="{{ $partner->title }}">
					@endforeach
				@endfor
			</div>
		</div>
	</div>

	<div class="business_contact sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Business Contact</p><strong>Business Contact</strong></div>
			<ul>
				<li class="i1"><div class="tt">TEL</div><p><strong>{{ $logisticsTel }}</strong></p></li>
				<li class="i2"><div class="tt">MAIL</div><p><strong>{{ $logisticsMail }}</strong></p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->

@endsection

@section('additional_styles')
<link rel="stylesheet" href="{{ asset('css/slick.css') }}" media="all">
@endsection

@section('additional_scripts')
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
