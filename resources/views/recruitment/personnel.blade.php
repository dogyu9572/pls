@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual personnel_top">
		<div class="inner">
			<div class="pagename">인사철학</div>
			<div class="tt">(주)피엘에스는 <br class="mo_vw"/><strong>존중과 신뢰를 바탕으로</strong><br/>구성원과 함께 <br class="mo_vw"/>성장하고 있습니다. </div>
			<p>성과를 공정하고 보상하고, <br class="mo_vw"/>발전을 위한 지원은 아까지 않습니다.</p>
		</div>
	</div>
	
	<div class="personnel personnel01 sub_padding">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Position Hierarchy</p><strong>직급체계</strong></div>
				<div class="con">
					<div class="tt">2단계 직급체계 구성 (사원– 매니저)<p>자율적 학습을 통한 자기 개발을 권장하며, 차세대 리더로 성장할 수 있도록 교육 프로그램을 지원하고 있습니다.</p></div>
					<ul>
						<li class="i1"><strong>사원</strong><p>육성단계</p></li>
						<li class="i2"><strong>매니저</strong><p>성과창출</p></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<div class="personnel personnel02">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Evaluation</p><strong>평가</strong></div>
				<div class="con">
					<div class="tt">성과 보상과 인재육성이 가능한 평가 시스템 운영</div>
					<div class="sistem_grapth bdrs20 tac">
						<ul>
							<li class="bdrs20 gbox_b i1">업적평가</li>
							<li class="bluebox bdrs20 i2">승진/보상</li>
							<li class="bdrs20 gbox_b i3">역량평가</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

</div> <!-- //container -->
@endsection
