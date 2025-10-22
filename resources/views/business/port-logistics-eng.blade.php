@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual port_logistics_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">New Value in Port Logistics <br/><strong>Value Chain</strong> Expansion</div>
			<p>(주)피엘에스 operates port logistics business (stevedoring, storage, transportation) based in Pyeongtaek Port, and promotes growth through cargo diversification and business area expansion based on stable port operation capabilities.</p>
		</div>
	</div>
	
	<div class="port_logistics01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Project Overview</p><strong>Business Overview</strong></div>
				<div class="con">
					<p>To establish a stable supply chain for Bio-mass power generation fuel supplied to GS Group power plants, we provide Total Logistics Service covering the entire process from port stevedoring of cargo imported from overseas to storage, management, and final transportation to power plants.<br/>
					Through this, we ensure the efficiency and reliability of the power generation fuel logistics process and support our customers' stable energy production activities.</p>
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
			<div class="stit mb"><p>Cargo handled in port logistics</p><strong>Port Logistics Cargo</strong></div>
			<ul>
				<li class="i1">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_psk01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_psk02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_psk03.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">PKS(Palm Kernel Shell)</div><p>A hard shell generated during palm oil extraction process, it is a stable energy source with calorific value of <strong>3,500~4,200 kcal/kg</strong>. With low moisture content and fibrous structure similar to nut shells, it is suitable for eco-friendly biomass fuel.</p>
					</div>
				</li>
				<li class="i2">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_woodpellet01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodpellet02.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodpellet03.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">Wood Pellet</div><p>An eco-friendly biomass fuel made by compressing wood byproducts, featuring high energy efficiency with calorific value of <strong>4,000~4,500 kcal/kg</strong> and low carbon emissions. It is utilized in various fields including power plants, industrial boilers, and heating fuels.</p>
					</div>
				</li>
				<li class="i3">
					<div class="port_logistics02_slide">
						<img src="{{ asset('images/img_port_logistics02_woodchip01.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodchip03.jpg') }}" alt="image">
						<img src="{{ asset('images/img_port_logistics02_woodchip02.jpg') }}" alt="image">
					</div>
					<div class="txt">
						<div class="tit">Wood Chip</div><p>A biomass fuel made by finely cutting and crushing wood, it is an economical energy source with calorific value of <strong>2,000~3,000 kcal/kg</strong>. With easy raw material procurement and excellent price competitiveness, it is widely used in power plants and industrial boilers.</p>
					</div>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="port_logistics03 sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Port Logistics Process</p><strong>Port Logistics Process</strong></div>
			<ul class="process_list set3">
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b01.jpg') }}" alt="image"></div><div class="txt"><span>01</span><p>Fuel Cargo Loading<em>North America/Vietnam/Indonesia</em></p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b02.jpg') }}" alt="image"></div><div class="txt"><span>02</span><p>Pyeongtaek/Dangjin Port Arrival</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b03.jpg') }}" alt="image"></div><div class="txt"><span>03</span><p>Stevedoring Operation</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b04.jpg') }}" alt="image"></div><div class="txt"><span>04</span><p>Indoor Storage</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b05.jpg') }}" alt="image"></div><div class="txt"><span>05</span><p>Transportation</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_b06.jpg') }}" alt="image"></div><div class="txt"><span>06</span><p>Power Plant Delivery (Outbound)</p></div></li>
			</ul>
		</div>
	</div>

	<div class="imported_automobiles04 sub_padding gbox_b">
		<div class="inner">
			<div class="stit mb"><p>Port Logistics Partner</p><strong>Port Logistics Business Partners</strong></div>
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
			<div class="stit mb"><p>Business Contact</p><strong>Business Inquiry</strong></div>
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
