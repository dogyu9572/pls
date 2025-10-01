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
									<dd>{{ $contact['pdi_manager1'] ?? '' }}</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>{{ $contact['pdi_phone1'] ?? '' }}</dd>
								</dl>
								<dl class="mt">
									<dt>PDI2 영업부</dt>
									<dd>{{ $contact['pdi_manager2'] ?? '' }}</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>{{ $contact['pdi_phone2'] ?? '' }}</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>{{ $contact['pdi_email'] ?? '' }}</dd>
								</dl>
							</div>
						</li>
						<li><div class="tt">항만물류사업</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>{{ $contact['logistics_manager'] ?? '' }}</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>{{ $contact['logistics_phone'] ?? '' }}</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>{{ $contact['logistics_email'] ?? '' }}</dd>
								</dl>
							</div>
						</li>
						<li><div class="tt">특장차 제조사업</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>{{ $contact['vehicle_manager'] ?? '' }}</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>{{ $contact['vehicle_phone'] ?? '' }}</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>{{ $contact['vehicle_email'] ?? '' }}</dd>
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
									<dd>{{ $contact['hr_manager'] ?? '' }}</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>{{ $contact['hr_phone'] ?? '' }}</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>{{ $contact['hr_email'] ?? '' }}</dd>
								</dl>
							</div>
						</li>
						<li><div class="tt">재무팀</div>
							<div class="dls">
								<dl>
									<dt>담당자</dt>
									<dd>{{ $contact['finance_manager'] ?? '' }}</dd>
								</dl>
								<dl>
									<dt>전화</dt>
									<dd>{{ $contact['finance_phone'] ?? '' }}</dd>
								</dl>
							</div>
							<div class="email">
								<dl>
									<dt>E-mail</dt>
									<dd>{{ $contact['finance_email'] ?? '' }}</dd>
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
