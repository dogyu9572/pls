@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }} no_aside">
	<div class="inner">
		<div class="term_tit"><strong>{{ $gName }}</strong></div>
		@if($policy && $policy->content)
			{!! $policy->content !!}
		@else
			<div class="terms_box">
				<p>No privacy policy has been registered.</p>
			</div>
		@endif
		
	</div>
</div> <!-- //container -->
@endsection
