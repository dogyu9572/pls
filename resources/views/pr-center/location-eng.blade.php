@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="map_area">
		<div id="daumRoughmapContainer1758587487503" class="root_daum_roughmap root_daum_roughmap_landing"></div>
		<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>
		<script charset="UTF-8">
			new daum.roughmap.Lander({
				"timestamp" : "1758587487503",
				"key" : "2b2g7ynfwt84",
				"mapWidth" : "1920",
				"mapHeight" : "640"
			}).render();
		</script>
	</div>

	<div class="inner">
		<div class="map_text bdrs20">
			<div class="logo"><img src="{{ asset('images/logo_w.svg') }}" alt="logo"></div>
			<ul>
				<li><strong>PLS HQ</strong><p>437-100 Seodong-daero, Poseung-eup, Pyeongtaek-si, Gyeonggi-do, Republic of Korea. <br class="hide">PLS Co., Ltd.</p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->

<script>
let checkMapLabel = setInterval(function() {
    let $label = $(".roughmap_maker_label");
    if ($label.length > 0) {
        $label.parent().prev().addClass("map_img");
        clearInterval(checkMapLabel);
    }
}, 50);
</script>
@endsection
