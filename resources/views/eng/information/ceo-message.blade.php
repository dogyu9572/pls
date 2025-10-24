@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="ceo_message_top"></div>
	<div class="ceo_message_btm">
		<div class="inner">
				<div class="tit">A Premier Comprehensive Logistics Company<br/><strong class="c_navy">PLS</strong></div>
				<div class="con">
					{!! $greeting->getCustomFieldsArray()['greeting_en'] ?? '' !!}
					<div class="name">{{ $greeting->getCustomFieldsArray()['ceo_message_en'] ?? '' }}</i> <i>Chief Executive Officer PLS Co., Ltd.</i></div>
				</div>
			
		</div>
	</div>

</div> <!-- //container -->
@endsection
