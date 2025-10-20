@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }} no_aside">
	<div class="inner">

		<div class="term_tit"><strong>{{ $gName }}</strong><div class="date">제정 {{ $enactmentDate }}</div></div>

		@if($reportingRules)
			{!! $reportingRules !!}
		@else
			<div class="terms_box">
				<p>등록된 내부신고제도 운영규정이 없습니다.</p>
			</div>
		@endif
		
	</div>
</div> <!-- //container -->
@endsection
