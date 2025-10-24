@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual safety_health_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">PLS Co., Ltd. places the safety and health of all employees and partners as its top priority, actively implementing the company’s <strong>Seven Safety and Health Management Principles</strong>.</div>
			<p>Through ISO 45001 certification, PLS has established a systematic framework for occupational safety and health management and continuous improvement activities. We are committed to creating a safe and healthy working environment where both employees and partners can work with confidence.</p>
		</div>
	</div>
	
	<div class="safety_health02 sub_padding pt0">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Management Policy</p><strong>Safety & Health <br/>Management Policy</strong></div>
				<div class="con gbox pd46 bdrs20">
					<ol class="num_ko_list">
						<li><strong>One.</strong> Management continuously expresses its commitment and takes the initiative to establish a strong safety and health culture.</li>
						<li><strong>Two.</strong> Safety and health goals and values are consistently shared among executives, employees, partner companies, and all workers.</li>
						<li><strong>Three.</strong> A safe and healthy working environment is ensured at every stage of business operations.</li>
						<li><strong>Four.</strong> All employees clearly understand and strictly comply with applicable safety and health laws, regulations, and company policies.</li>
						<li><strong>Five.</strong> Safety and health risks are regularly identified and addressed in advance to prevent incidents.</li>
						<li><strong>Six.</strong> Safety and health processes are continuously reviewed, improved, and effectively operated.</li>
						<li><strong>Seven.</strong> Employees are guaranteed the right to make suggestions and participate in discussions regarding safety and health matters.</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<div class="safety_health03 sub_padding gbox">
		<div class="inner">
			<div class="stit mb"><p>Implementation Objectives</p><strong>Safety and Health Objectives</strong></div>
			<ul>
				<li class="bg1"><div class="tt">Achieve “Zero Accidents” through Safety Awareness</div><p>Strengthen employees’ safety awareness through regular safety and health education programs to achieve a zero-accident workplace.</p></li>
				<li class="bg2"><div class="tt">Ensure Compliance with Safety and Health Regulations</div><p>Thoroughly comply with all applicable safety, health, and regulatory requirements to establish a strong and lasting safety culture within the organization.</p></li>
				<li class="bg3"><div class="tt">Promote Employee Participation in Risk Assessment and Communication</div><p>Encourage active participation of employees in identifying and evaluating workplace hazards, implementing corrective actions, and enhancing communication to continuously improve overall safety and health performance.</p></li>
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
