@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual special_vehicle_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">글로벌 제조 경쟁력<br/><strong>Aircraft Refueller</strong></div>
			<p>㈜피엘에스는 2016년 중동 지역에 <br class="mo_vw"/>항공기 급유차를 수출한 것을 시작으로, <br class="pc_vw"/>현재까지 <br class="mo_vw"/>국내외에 200대 이상의 항공기 급유차를 제조 및 <br class="mo_vw"/>수출 해왔으며, <br class="pc_vw"/>지속적인 신규 시장 개척을 통해 <br class="mo_vw"/>글로벌 경쟁력을 확대해 나가고자 합니다.</p>
		</div>
	</div>
	
	<div class="special_vehicle01 sub_padding pt0">
		<div class="inner">
			<div class="stit mb"><p>Special-purpose Vehicle Manufacturing Process</p><strong>특장차 제작 Process</strong></div>
			<ul class="process_list set3">
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_c01.jpg') }}" alt="image"></div><div class="txt"><span>01</span><p>Bid/Contract</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_c02.jpg') }}" alt="image"></div><div class="txt"><span>02</span><p>Purchase Chassis</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_c03.jpg') }}" alt="image"></div><div class="txt"><span>03</span><p>Manufacturing Tank module</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_c04.jpg') }}" alt="image"></div><div class="txt"><span>04</span><p>Inspection</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_c05.jpg') }}" alt="image"></div><div class="txt"><span>05</span><p>Final</p></div></li>
				<li><div class="imgfit"><img src="{{ asset('images/img_pdiprocess_c06.jpg') }}" alt="image"></div><div class="txt"><span>06</span><p>Shipment</p></div></li>
			</ul>
		</div>
	</div>

	<div class="special_vehicle02 sub_padding gbox">
		<div class="inner">
			<div class="dl_area">
				<div class="stit mb"><p>Delivery Record</p><strong>납품실적</strong></div>
				<div class="con">
					<ul>
						<li><img src="{{ asset('images/img_special_vehicle02_01.jpg') }}" alt="image"><p>5,000 Gal</p></li>
						<li><img src="{{ asset('images/img_special_vehicle02_02.jpg') }}" alt="image"><p>5,000 Gal</p></li>
						<li><img src="{{ asset('images/img_special_vehicle02_03.jpg') }}" alt="image"><p>10,000 Gal</p></li>
						<li><img src="{{ asset('images/img_special_vehicle02_04.jpg') }}" alt="image"><p>10,000 Gal</p></li>
						<li><img src="{{ asset('images/img_special_vehicle02_05.jpg') }}" alt="image"><p>Special order</p></li>
						<li><img src="{{ asset('images/img_special_vehicle02_06.jpg') }}" alt="image"><p>Special order</p></li>
					</ul>
					<div class="tbl">
						<table>
							<tbody>
								<tr>
									<th>Tank Capacity</th>
									<td>2,500 US Gallons ~20,000 US Gallons</td>
								</tr>
								<tr>
									<th>Applied Vehicle</th>
									<td>5~25ton Rigid Chassis</td>
								</tr>
								<tr>
									<th>Tank Material</th>
									<td>Aluminum Alloy, SUS Tank</td>
								</tr>
								<tr>
									<th>Fuelling Module</th>
									<td>Pump, Filter Vessel, Flow Meter, Hose & Hose Reel Nozzles(1.5 & 2.5 lnch), <br class="pc_vw">Pressure Control Valve, Bypass Valve, Bypass Valve ETC</td>
								</tr>
								<tr>
									<th>Tank Module</th>
									<td>Level Gauge, Jet-Lever Sense, Internal Valve, Emergency Valve, Air Vent Valve, <br class="pc_vw">Bottom Load Adapter, Manholes ETC</td>
								</tr>
								<tr>
									<th>Safety Feature</th>
									<td>Brake inter-lock system, Emergency shut-off control system, Static ground reels, Fire extinguishers, ETC.</td>
								</tr>
								<tr>
									<th>Applicable Laws</th>
									<td>JIGS, IATA, EI(API/IP), NFPA</td>
								</tr>
								<tr>
									<th>Basic Specifications</th>
									<td>Hose Box, Suction Hose, Tool Box, Fender, Guardrail, Bumper</td>
								</tr>
								<tr>
									<th>Optional</th>
									<td>Lifting Platform, Deck Fuellling System, Commercial Type, Military Type</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="business_contact sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Business Contact</p><strong>사업문의</strong><a href="#this" class="btn_download flex_center">브로셔 다운로드</a></div>
			<ul>
				<li class="i1"><div class="tt">TEL</div><p><strong>송민호 매니저  031-684-9661</strong></p></li>
				<li class="i2"><div class="tt">MAIL</div><p><strong>mhsong@plscorp.co.kr</strong></p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->
@endsection
