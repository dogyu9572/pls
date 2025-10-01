@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }}">
	
	<div class="contact_us">
		<div class="inner">

			<div class="imgfit bdrs20"><img src="{{ asset('images/img_contact_us01.jpg') }}" alt="image"></div>
			<div class="dl_area">
				<div class="stit"><p>Business</p><strong>영업</strong></div>
				<div class="con">
					<ul>
						<li><div class="tt">PDI사업</div>
							<div class="dls">
								<dl>
									<dt>PDI1 영업부</dt>
									<dd>여준동 부장</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>031-684-9661</dd>
								</dl>
								<dl class="mt">
									<dt>PDI2 영업부</dt>
									<dd>김창호 부장</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>031-686-9661</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>
										<p>1영업부 jdyeo@plscorp.co.kr</p>
										<p>2영업부 66852001@plscorp.co.kr</p>
									</dd>
								</dl>
							</div>
						</li>
						<li><div class="tt">항만물류사업</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>이승욱 매니저</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>031-684-9662</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>swlee@plscorp.co.kr</dd>
								</dl>
							</div>
						</li>
						<li><div class="tt">특장차 제조사업</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>송민호 매니저</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>031-684-9661</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>mhsong@plscorp.co.kr</dd>
								</dl>
							</div>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="imgfit bdrs20"><img src="{{ asset('images/img_contact_us02.jpg') }}" alt="image"></div>
			<div class="dl_area">
				<div class="stit"><p>Business Support</p><strong>경영지원</strong></div>
				<div class="con">
					<ul>
						<li><div class="tt">인사/총무팀</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>박세현 매니저</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>031-684-9663, 9665</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>shpark@plscorp.co.kr</dd>
								</dl>
							</div>
						</li>
						<li><div class="tt">재무팀</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>오주석 매니저</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>031-684-9664, 9668</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>jsoh1234@plscorp.co.kr</dd>
								</dl>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

</div> <!-- //container -->

<script>
$(function(){
	var $dls = $('.contact_us .dls'), resizeTimer;

	function setEqualHeight(){
		$dls.css('height','auto');
		
		if ($(window).width() <= 767) return;
		
		var max = 0;
		$dls.each(function(){ max = Math.max(max, $(this).outerHeight()); });
		$dls.css('height', max + 'px');
	}

	$(window).on('load', setEqualHeight);
	setEqualHeight();
	$(window).on('resize', function(){ 
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(setEqualHeight, 100);
	});
});
</script>
@endsection
