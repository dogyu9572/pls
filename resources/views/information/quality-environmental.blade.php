@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual quality_environmental_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt"><span class="roboto">㈜</span>피엘에스는 <br class="mo_vw"/>고객 만족 실현과 <br/>최고의 <br class="mo_vw"/>고객 가치를 창출하기 위해 <br/><strong>품질 및 환경 경영 시스템</strong>을 <br class="mo_vw"/>도입하여 <br class="pc_vw"/>운영하고 <br class="mo_vw"/>있습니다.</div>
			<p>당사는 지속적인 품질 향상 노력은 물론, <br class="mo_vw"/>환경 리스크 또한 체계적으로 관리함으로써 <br/>고객에게 최적의 솔루션과 차별화된 서비스를 <br class="mo_vw"/>제공하고자 끊임없이 노력하고 있습니다.</p>
		</div>
	</div>
	
	<div class="quality_environmental_btm">
		<div class="inner">
			<div class="quality_environmental_graph">
				<div class="tit">품질/환경경영시스템지속적 개선</div>
				<ul class="flex_center">
					<li class="box i1 bg_b">고객 요구사항</li>
					<li class="center">
						<ul class="flex_center">
							<li class="box i2 bg_s">경영책임</li>
							<li class="box i3 bg_s">경영자원의 관리</li>
							<li class="box i4 bg_s">서비스 실현</li>
							<li class="box i5 bg_s">측정/분석 개선</li>
						</ul>
					</li>
					<li class="box i6 bg_b">고객만족</li>
				</ul>
			</div>

			<div class="iso_area">
				@forelse($certifications as $certification)
					<div class="box">
						@if($certification->thumbnail)
							<div class="imgfit"><img src="{{ asset('storage/' . $certification->thumbnail) }}" alt="{{ $certification->title }}"></div>
						@endif
						<div class="con">
							<img src="{{ asset('images/icon_iso.png') }}" alt="iso">
							<div class="tt">{{ $certification->title }}</div>
							<div>{!! $certification->content !!}</div>
						</div>
					</div>
				@empty
					<div class="box">
						<div class="con">
							<p>등록된 인증서가 없습니다.</p>
						</div>
					</div>
				@endforelse
			</div>

		</div>
	</div>

</div> <!-- //container -->
@endsection
