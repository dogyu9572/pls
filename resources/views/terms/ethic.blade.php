@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }} no_aside">
	<div class="inner">

		<div class="term_tit"><strong>{{ $gName }}</strong></div>

		@if($ethic && $ethic->content)
			{!! $ethic->content !!}
		@else
			<div class="terms_box">
				<p>등록된 윤리규범이 없습니다.</p>
			</div>
		@endif
		
	</div>
</div> <!-- //container -->
@endsection
