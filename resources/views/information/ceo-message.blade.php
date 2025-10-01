@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="ceo_message_top"></div>
	<div class="ceo_message_btm">
		<div class="inner">
				<div class="tit">초일류 종합물류기업<br/><strong class="c_navy"><span class="roboto">㈜</span>피엘에스</strong></div>
				<div class="con">
					{!! $greeting->custom_fields['greeting_ko'] !!}
					<div class="name"><span class="roboto">㈜</span>피엘에스 대표이사<i>{{ $greeting->custom_fields['ceo_message_ko'] }}</i></div>
				</div>
			
		</div>
	</div>

</div> <!-- //container -->
@endsection
