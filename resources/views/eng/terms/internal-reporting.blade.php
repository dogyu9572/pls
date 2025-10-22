@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }} no_aside">
	<div class="inner">

		<div class="term_tit"><strong>{{ $gName }}</strong><div class="date">Enacted {{ $enactmentDate }}</div></div>

		@if($reportingRules)
			{!! $reportingRules !!}
		@else
			<div class="terms_box">
				<p>No internal reporting system operation regulations have been registered.</p>
			</div>
		@endif
		
	</div>
</div> <!-- //container -->
@endsection
