@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual ethical_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">(주)피엘에스는 <br class="mo_vw"/>모든 경영활동에서 <br><strong>'정도(正道)를 걷는 <br class="mo_vw"/>윤리경영'</strong>을 <br class="pc_vw">실천하고 <br class="mo_vw"/>있습니다.</div>
			<p>윤리헌장을 제정하여 윤리적 판단 기준을 명확히 하고, <br class="mo_vw"/>전 임직원이 투명하고 공정하며 <br class="pc_vw">합리적으로 업무를 <br class="mo_vw"/>수행할 수 있도록 지속적으로 노력하고 있습니다.</p>
		</div>
	</div>
	
	<div class="ethical01">
		<div class="inner">
			<div class="stit mb"><p>PLS Code of Ethics</p><strong>피엘에스 윤리헌장</strong></div>
			<dl>
				<dt><i></i>윤리경영<em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em></dt>
				<dd class="i1"><div class="tt">하나, 공정경영</div><p>제반법규를 준수하고 사회적 가치관을 존중하며, <br class="pc_vw">정직하고 공정한 업무자세로 공정경영을 실천하겠습니다.</p></dd>
				<dd class="i2 tar"><div class="tt">둘, 고객만족</div><p>최상의 서비스로 고객의 권익을 증진시키고, 고객만족의 자세를 <br class="pc_vw">견지하여 고객으로부터 신뢰받는 기업이 되겠습니다.</p></dd>
				<dd class="i3"><div class="tt">셋, 화합과 협력</div><p>투명한 거래질서를 확립하고 협력업체와 상호 신뢰, 협력을 바탕으로 <br class="pc_vw">장기적인 공존과 공동 발전을 추구하겠습니다.</p></dd>
				<dd class="i4 tar"><div class="tt">넷, 함께하는 사회</div><p>건전한 기업활동을 통해 <br class="pc_vw">사회적 가치창출에 기여하겠습니다.</p></dd>
			</dl>
		</div>
	</div>
	
	<div class="ethical02 gbox sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Pledge of Practice</p><strong>윤리경영 실천 서약서</strong></div>
			<div class="wbox pd68">
				<div class="tit">선서</div>
				<p class="tb">피엘에스 임직원은 깨끗하고 투명한 기업을 만드는데 앞장서기 위해 다음 사항을 준수할 것을 서약합니다.</p>
				<ol class="num_list">
					<li><strong>1</strong>윤리규범을 준수하여 피엘에스의 윤리경영 목표가 실현되도록 노력하겠습니다.</li>
					<li><strong>2</strong>어떠한 불공정거래 및 부정행위를 하지 않겠으며, 다른 임직원의 부정·비리 행위를 인지하였을 경우에는 즉시 감사책임자에게 보고하겠습니다.</li>
					<li><strong>3</strong>윤리규범을 위배하는 불공정거래 및 분정·비리·행위여부에 대한 정기 및 수시조사가 진행될 경우 회사가 요청하는 관련자료의 제출 등 모든 협조를 다하겠습니다.</li>
					<li><strong>4</strong>업무수행에 있어 피엘에스人으로서 내린 제반결정이나 행동이 양심에 한점 부끄럼이 없도록 노력하겠습니다.</li>
					<li><strong>5</strong>위의 모든 사항을 준수하고 적극 실천하여 피엘에스의 모범 되도록 하겠습니다.</li>
				</ol>
				<div class="date">2023.12.08<strong>임직원 일동</strong><i></i></div>
			</div>
		</div>
	</div>
	
	<div class="ethical03">
		<div class="inner">
			<div class="btns flex_center">
				<a href="{{ route('terms.ethic') }}" class="btn_line flex_center">피엘에스 윤리규범</a>
				<a href="{{ route('terms.internal-reporting') }}" class="btn_line flex_center">내부신고제도 운영규정</a>
				<a href="mailto:ethics@plscorp.co.kr" class="btn_mail flex_center">PLS 제보하기</a>
			</div>
		</div>
	</div>

</div> <!-- //container -->
@endsection
