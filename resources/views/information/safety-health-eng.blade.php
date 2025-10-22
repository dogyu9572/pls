@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual safety_health_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">(주)피엘에스 considers the safety and health of all employees and partners as the top priority value and actively practices the <strong>7 Safety and Health Management Policies</strong>.</div>
			<p>We are conducting systematic safety and health management and improvement activities through ISO45001 certification, and are striving to create a healthy working environment where all employees and partners can work with peace of mind.</p>
		</div>
	</div>
	
	<div class="safety_health02 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>safety_health_top</p><strong>Safety & Health <br/>Management Policy</strong></div>
				<div class="con gbox pd46 bdrs20">
					<ol class="num_ko_list">
						<li><strong>One.</strong> Management continuously expresses their will to establish a safety and health culture and takes the lead.</li>
						<li><strong>Two.</strong> Safety and health goals and values are constantly shared with management, employees, partner companies and workers.</li>
						<li><strong>Three.</strong> A safe and healthy working environment is secured at all stages of business execution.</li>
						<li><strong>Four.</strong> Safety and health related laws and company regulations are clearly recognized and strictly followed.</li>
						<li><strong>Five.</strong> Safety and health risks are identified in advance and measures are taken periodically.</li>
						<li><strong>Six.</strong> Safety and health work processes are continuously checked and improved for operation.</li>
						<li><strong>Seven.</strong> Workers are guaranteed to be able to suggest and discuss safety and health.</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="safety_health03 sub_padding gbox">
		<div class="inner">
			<div class="stit mb"><p>Implementation Objectives</p><strong>Safety & Health Implementation Goals</strong></div>
			<ul>
				<li class="bg1"><div class="tt">Achieve "ZERO" safety accidents through raising safety awareness</div><p>Strengthen workers' safety awareness through periodic safety and health education</p></li>
				<li class="bg2"><div class="tt">Compliance with safety-related laws</div><p>Establish safety culture by strictly following safety and health related laws and regulations</p></li>
				<li class="bg3"><div class="tt">Worker participation risk assessment and enhanced communication</div><p>Continuously improve safety and health levels by identifying and improving harmful and dangerous factors in the workplace through participation with workers and enhancing communication</p></li>
			</ul>
		</div>
	</div>

	<div class="safety_health04">
		<div class="inner">
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
			<a href="mailto:{{ $safetyHealthEmail }}" class="btn_mail flex_center">Safety & Health Suggestion Channel</a>
		</div>
	</div>

</div> <!-- //container -->
@endsection
