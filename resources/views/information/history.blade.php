@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual history_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt"><span class="roboto">㈜</span>피엘에스는 <br class="mo_vw"/>도전과 혁신을 바탕으로<br/><strong>글로벌 물류 기업</strong>으로 <br class="mo_vw"/>성장하고 있습니다.</div>
			<p>평택항자유무역지역 이전을 기점으로 <br class="mo_vw"/>사업영역을 다변화하고, <br class="pc_vw"/>미래 성장을 위한 <br class="mo_vw"/>인프라 투자를 지속적으로 진행하고 있습니다</p>
		</div>
	</div>

	<div class="history_btm">
		<div class="point" id="start"></div>
		<div class="point" id="end"></div>
		<div class="inner">
			@if(!empty($availableDecades))
			<ul class="years year{{ $availableDecades[0] ?? 2020 }}">
				@foreach($availableDecades as $index => $decade)
				<li class="{{ $index === 0 ? 'on' : '' }}"><a href="#year{{ $decade }}">{{ $decade }}</a></li>
				@endforeach
			</ul>
			@endif
			<div class="list">
				<div class="line"><div class="bar"></div></div>
			
				@foreach($availableDecades as $decade)
					<div class="box">
						<div class="point" id="year{{ $decade }}"></div>
						<ul>
							@if(isset($groupedByDecade[$decade]) && $groupedByDecade[$decade]->isNotEmpty())
								@foreach($groupedByDecade[$decade] as $year => $histories)
									<li>
										<div class="year">{{ $year }}</div>
										@foreach($histories as $history)
											<div class="dots_list">
												{!! $history->content !!}
											</div>
											@if($history->attachments && is_array($history->attachments) && count($history->attachments) > 0)
												@php
													$firstAttachment = $history->attachments[0];
												@endphp
												<div class="imgfit">
													<img src="{{ asset('storage/' . $firstAttachment['path']) }}" alt="image">
												</div>
											@endif
										@endforeach
									</li>
								@endforeach
							@endif
						</ul>
					</div>
				@endforeach
			</div>
		</div>
	</div>

</div> <!-- //container -->

<script>
$(window).on("scroll resize", function () {
    let scrollTop = $(window).scrollTop();
    let winH = $(window).height();
	let header = $(".header").outerHeight() || 0;
	let yearsH = $(".history_btm .years").outerHeight() || 0;
	$("#end").css("bottom", yearsH + header + "px");

    // years 갱신
    $(".history_btm .list .point").each(function () {
        let pointTop = $(this).offset().top;
        let id = $(this).attr("id");

        if (scrollTop >= pointTop - 10) {
            $(".history_btm .years li").removeClass("on");
            $(".history_btm .years li a[href='#" + id + "']")
                .parent().addClass("on");
        }
    });

    // bar 높이 제어
    let line = $(".history_btm .list .line");
    let bar = line.find(".bar");

    if (line.length) {
        let lineTop = line.offset().top;
        let lineH = line.outerHeight();
        let triggerTop = lineTop - winH / 2;
        let maxScroll = lineTop + lineH - winH / 2;

        if (scrollTop >= triggerTop) {
            let progress = (scrollTop - triggerTop) / (maxScroll - triggerTop);
            progress = Math.min(Math.max(progress, 0), 1);
            let barH = lineH * progress;
            bar.css("height", barH + "px");
        } else {
            bar.css("height", "0");
        }
    }
});

$(window).scroll(function () {
	if ($(window).scrollTop() > $('#start').offset().top) {
		$('.history_btm').addClass("start").removeClass("end");
	} else {
		$('.history_btm').removeClass("start");
	}
});

$(window).scroll(function () {
	if ($(window).scrollTop() > $('#end').offset().top) {
		$('.history_btm').addClass("end").removeClass("start");
	} else {
		$('.history_btm').removeClass("end");
	}
});

$(".history_btm .years a").on("click", function (e) {
	e.preventDefault();
	let target = $(this).attr("href");
	let offset = $(target).offset().top;

	$("html, body").stop().animate({ scrollTop: offset }, 600);
});
</script>
@endsection
