@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual safety_health_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">(주)피엘에스는 <br class="mo_vw"/>모든 임직원과 협력사의 <br>안전과 건강을 최우선 <br class="mo_vw"/>가치로 여기며, <br><strong>7대 안전보건 경영방침</strong>을 <br class="mo_vw"/>적극 실천하고 있습니다.</div>
			<p>ISO45001 인증을 통해 체계적인 안전보건 관리와 <br class="mo_vw"/>개선 활동을 진행하고 있으며, <br class="pc_vw">임직원과 협력사 모두가 <br class="mo_vw"/>안심할 수 있는 건강한 작업 환경을 만들기 위해 <br class="mo_vw"/>노력하고 있습니다.</p>
		</div>
	</div>
	
	<div class="safety_health02 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>safety_health_top</p><strong>안전보건 <br/>경영방침</strong></div>
				<div class="con gbox pd46 bdrs20">
					<ol class="num_ko_list">
						<li><strong>하나.</strong> 경영자는 안전보건 문화 정착을 위한 의지를 지속적으로 표명하고 솔선수범 한다.</li>
						<li><strong>둘.</strong>안전보건 목표 및 가치는 경영층, 직원, 협력회사 및 근로자에게 상시 공유한다.</li>
						<li><strong>셋.</strong>사업의 모든 수행단계에서 안전하고 건강한 작업환경을 확보한다.</li>
						<li><strong>넷.</strong>안전보건 관련 법규와 회사 규정을 명확히 인지하고 철저히 준수한다.</li>
						<li><strong>다섯.</strong>주기적으로 안전보건 Risk를 사전에 파악하여 조치한다.</li>
						<li><strong>여섯.</strong>안전보건 업무 Process를 지속적으로 점검 및 개선하여 운영한다.</li>
						<li><strong>일곱.</strong>근로자가 안전보건에 대해 건의하고, 협의할 수 있도록 보장한다.</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="safety_health03 sub_padding gbox">
		<div class="inner">
			<div class="stit mb"><p>Implementation Objectives</p><strong>안전보건 추진목표</strong></div>
			<ul>
				<li class="bg1"><div class="tt">안전의식 고취로 안전사고 "ZERO"화</div><p>주기적인 안전보건교육으로 <br>근로자의 안전의식 강화</p></li>
				<li class="bg2"><div class="tt">안전관련 법규 준수</div><p>안전보건관련 법규 및 규정을 <br>철저히 준수하여 안전문화 정착</p></li>
				<li class="bg3"><div class="tt">근로자 참여 위험성평가 및 소통강화</div><p>근로자와 함께 참여하여 사업장의 <br>유해위험요소를 파악하여 개선조치를 <br>시행하고, 소통강화를 안전보건수준을 <br>지속적으로 향상</p></li>
			</ul>
		</div>
	</div>

	<div class="safety_health04">
		<div class="inner">
			<div class="iso_area">
				<div class="box">
					<div class="imgfit"><img src="{{ asset('images/img_safety_health_iso01.jpg') }}" alt="image"></div>
					<div class="con">
						<img src="{{ asset('images/icon_iso.png') }}" alt="iso">
						<div class="tt">ISO 45001:2018 (안전/보건경영시스템)</div>
						<p>ISO 45001 인증은 사업장에서 발생할 수 있는 각종 위험을 사전 예측 및 예방하여 궁극적으로 <br class="pc_vw">
						기업의 이윤창출에 기여하고 조직의 안전보건을 체계적으로 관리하기 위한 요구사항을 규정한 국제표준으로, <br class="pc_vw">
						피엘에스는 2025년 ISO 45001 인증을 취득하였습니다.</p>
					</div>
				</div>
			</div>
			<a href="mailto:dhyim69@plscorp.co.kr" class="btn_mail flex_center">안전보건 제안 채널</a>
		</div>
	</div>

</div> <!-- //container -->
@endsection
