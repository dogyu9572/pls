@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum ?? '' }} no_aside">
	<div class="inner">

		<div class="term_tit"><strong>{{ $gName }}</strong></div>

		@if($emailRejection)
			{!! $emailRejection->content !!}
		@else
			<div class="terms_area">
				<div class="gbox bdrs20">This website refuses unauthorized collection of email addresses posted on this website using email collection programs or other technical devices, and please note that violation of this will be punished under the Information and Communications Network Act.</div>
			</div>

			<div class="terms_box">
				<div class="tit_top">Information and Communications Network Act</div>
				<div class="tit">Article 50-2 (Prohibition of Unauthorized Collection of Electronic Mail Addresses, etc.)</div>
				<ol class="num">
					<li>No person shall collect electronic mail addresses using programs that automatically collect electronic mail addresses from Internet websites that clearly indicate the intention to refuse collection of electronic mail addresses, or other technical devices.</li>
					<li>No person shall sell or distribute electronic mail addresses collected in violation of paragraph (1).</li>
					<li>No person shall use electronic mail addresses collected in violation of paragraph (1) to send electronic mail that the recipient does not wish to receive.</li>
				</ol>
			</div>
		@endif
		
	</div>
</div> <!-- //container -->
@endsection
