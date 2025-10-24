@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual special_vehicle_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">Global Manufacturing Competitiveness<br/><strong>Aircraft Refueller</strong></div>
			<p>Since exporting its first <strong>aircraft refueller</strong> to the Middle East in 2016, PLS Co., Ltd. has manufactured and exported <strong>over 200 units</strong> of aircraft refuellers both domestically and internationally.<br/>
			Through continuous development of new markets and technological innovation, PLS aims to further strengthen its <strong>global competitiveness</strong> as a trusted special vehicle manufacturer.</p>
		</div>
	</div>
	
	<div class="special_vehicle01 sub_padding pt0">
		<div class="inner">
			<div class="stit mb"><p>Special-purpose Vehicle Manufacturing Process</p><strong>Special Vehicle Manufacturing Process</strong></div>
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
				<div class="stit mb"><p>Delivery Record</p><strong>Delivery <br class="pc_vw"/>Record</strong></div>
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
			<div class="stit mb"><p>Business Contact</p><strong>Business Inquiry</strong>@if($brochureUrl)<a href="{{ $brochureUrl }}" class="btn_download flex_center" download="{{ $brochureFileName ?? 'brochure.pdf' }}">Download Brochure</a>@endif</div>
			<ul>
				<li class="i1"><div class="tt">TEL</div><p><strong>{{ $specialVehicleTel }}</strong></p></li>
				<li class="i2"><div class="tt">MAIL</div><p><strong>{{ $specialVehicleMail }}</strong></p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->
@endsection
