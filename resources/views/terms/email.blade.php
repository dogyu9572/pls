@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }} no_aside">
	<div class="inner">

		<div class="term_tit"><strong>{{ $gName }}</strong></div>

		@if($emailRejection)
			{!! $emailRejection->content !!}
		@else
			<div class="terms_area">
				<div class="gbox bdrs20">본 웹사이트에 게시된 이메일 주소가 전자우편 수집 프로그램이나 그 밖의 기술적 장치를 이용하여 무단으로 수집되는 것을 거부하며, <br class="pc_vw"/>이를 위반시 정보통신망법에 의해 처벌됨을 유념하시기 바랍니다.</div>
			</div>

			<div class="terms_box">
				<div class="tit_top">정보통신망 이용촉진 및 정보보호 등에 관한 법률</div>
				<div class="tit">제50조의 2 (전자우편조수의 무단 수집행위 등 금지)</div>
				<ol class="num">
					<li>누구든지 전자우편주소의 수집을 거부하는 의사 명시된 인터넷 홈페이지에서 자동으로 전자우편주소를 수집하는 프로그램 그 밖의 기술적 장치를 이용하여 전자우편주소를 수집하여서는 아니된다.</li>
					<li>누구든지 제1항의 규정을 위반하여 수집된 전자우편주소를 판매·유통하여서는 아니된다.</li>
					<li>누구든지 제1항 및 제2항의 규정에 의하여 수집·판매 및 유통이 금지된 전자우편주소임을 알고 이를 정보 전송에 이용하여서는 아니된다.<br/>[본조신설 2002.12.18]</li>
				</ol>

				<div class="tit">제65조의2 (벌칙)</div>
				<p>다음 각호의 1에 해당하는 자는 1천만원 이하의 벌금에 처한다.</p>
				<ol class="num">
					<li>제50조제4항의 규정을 위반하여 기술적 조치를 한 자</li>
					<li>제50조제6항의 규정을 위반하여 영리목적의 광고성 정보를 전송한 자</li>
					<li>제50조의2의 규정을 위반하여 전자우편주소를 수집ㆍ판매ㆍ유통 또는 정보전송에 이용한 자</li>
				</ol>
				<p>[본조신설 2002.12.18]</p>
			</div>
		@endif
		
	</div>
</div> <!-- //container -->
@endsection
