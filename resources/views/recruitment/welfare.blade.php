@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual welfare_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt"><span class="roboto">㈜</span>피엘에스는 <br class="mo_vw"/>임직원이 일과 삶의 <br class="mo_vw"/>균형을 이루며 <br class="pc_vw"/>풍요로운 삶을 누릴 수 있도록 <br/><strong>다양한 복리후생 제도</strong>를 <br class="mo_vw"/>운영하고 있습니다.</div>
			<p>생활 안정은 물론, 건전한 취미와 <br class="mo_vw"/>여가 활동을 위한 지원을 통해 <br class="pc_vw"/>구성원이 자기 발전에 <br class="mo_vw"/>전념할 수 있는 환경을 만들어가고 있습니다.</p>
		</div>
	</div>
	
	<div class="welfare welfare01 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Lifestyle Support</p><strong>생활지원</strong></div>
				<div class="con">
					<ul class="support_list set1">
						<li class="i01">학자금 지원</li>
						<li class="i02">의료비 및 <br/>단체상해보험</li>
						<li class="i03">경조금 / <br/>경조휴가</li>
						<li class="i04">보육비</li>
						<li class="i05">명절선물</li>
						<li class="i06">휴양시설</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<div class="welfare welfare02 sub_padding pb_container gbox">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Work-Life Support</p><strong>직장 <br/>생활지원</strong></div>
				<div class="con">
					<ul class="support_list set2">
						<li class="i01">자기개발비</li>
						<li class="i02">통신비</li>
						<li class="i03">중식/석식 제공</li>
						<li class="i04">건강검진 및 <br/>예방접종</li>
						<li class="i05">장기근속상</li>
						<li class="i06">우수사원 포상</li>
						<li class="i07">동호회 운영</li>
						<li class="i08">기숙사 운영</li>
						<li class="i09">근무복 제공</li>
						<li class="i10">통근버스 운영<p>안중/포승노선</p></li>
						<li class="i11">교육훈련 지원</li>
						<li class="i12">사내 체육시설 운영<p>탁구/당구장</p></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

</div> <!-- //container -->
@endsection
