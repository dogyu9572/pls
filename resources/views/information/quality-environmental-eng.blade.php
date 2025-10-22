@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual quality_environmental_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt"><span class="roboto">㈜</span>PLS operates a <br/><strong>Quality and Environmental Management System</strong><br/>to achieve customer satisfaction and create the best customer value.</div>
			<p>We continuously strive to provide optimal solutions and differentiated services to customers by not only continuously improving quality but also systematically managing environmental risks.</p>
		</div>
	</div>
	
	<div class="quality_environmental_btm">
		<div class="inner">
			<div class="quality_environmental_graph">
				<div class="tit">Quality/Environmental Management System Continuous Improvement</div>
				<ul class="flex_center">
					<li class="box i1 bg_b">Customer Requirements</li>
					<li class="center">
						<ul class="flex_center">
							<li class="box i2 bg_s">Management Responsibility</li>
							<li class="box i3 bg_s">Management of Management Resources</li>
							<li class="box i4 bg_s">Service Realization</li>
							<li class="box i5 bg_s">Measurement/Analysis Improvement</li>
						</ul>
					</li>
					<li class="box i6 bg_b">Customer Satisfaction</li>
				</ul>
			</div>

			<div class="iso_area">
				@forelse($certifications as $certification)
					<div class="box">
						<div class="imgfit"><img src="{{ $certification->thumbnail ? asset('storage/' . $certification->thumbnail) : asset('images/default.jpg') }}" alt="{{ $certification->title }}"></div>
						<div class="con">
							<img src="{{ asset('images/icon_iso.png') }}" alt="iso">
							<div class="tt">{{ $certification->title }}</div>
							<div>{!! $certification->content !!}</div>
						</div>
					</div>
				@empty
					<div class="box">
						<div class="con">
							<p>No registered certifications.</p>
						</div>
					</div>
				@endforelse
			</div>

		</div>
	</div>

</div> <!-- //container -->
@endsection
