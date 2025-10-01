@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="ceo_message_top"></div>
	<div class="ceo_message_btm">
		<div class="inner">
			<div class="tit">초일류 종합물류기업<br/><strong class="c_navy"><span class="roboto">㈜</span>피엘에스</strong></div>
			<div class="con">
				<strong>PLS 홈페이지를 방문해 주신 여러분을 진심으로 환영합니다.</strong>
				<p>2009년 GS그룹 종합상사인 GS글로벌의 물류 자회사로 출범한 PLS는 평택항 자유무역지역을 거점으로 고객에게 최상의 물류 솔루션을 제공하는 전문 Logistics Provider로 성장해왔습니다.</p>
				<p>PLS는 수입자동차 PDI사업을 핵심으로 항만물류, 특장차제조 및 태양광발전으로 사업영역을 확장하고 있으며, 앞으로도 글로벌 물류리더로 도약하기 위해 혁신과 성장을 향해 전력을 다하겠습니다. <br/>
				PLS의 전임직원은 고객의 신뢰를 최우선 가치로 삼고 고객과 함께하는 지속 가능한 성장을 통해 신뢰받는 기업으로 나아가겠습니다.</p>
				<p>감사합니다.</p>
				<div class="name"><span class="roboto">㈜</span>피엘에스 대표이사<i><img src="{{ asset('images/img_name.svg') }}" alt="김태원"></i></div>
			</div>
		</div>
	</div>

</div> <!-- //container -->
@endsection
