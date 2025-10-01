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
			<ul class="years year2020">
				<li class="on"><a href="#year2020">2020</a></li>
				<li><a href="#year2010">2010</a></li>
				<li><a href="#year2000">2000</a></li>
				<li><a href="#year1990">1990</a></li>
			</ul>
			<div class="list">
				<div class="line"><div class="bar"></div></div>
			
				<div class="box">
					<div class="point" id="year2020"></div>
					<ul>
						<li><div class="year">2025</div>
							<div class="dots_list">
								<p>PLS 태양광 발전시설 준공 (발전사업 개시)</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2025.jpg') }}" alt="image"></div>
						</li>
						<li><div class="year">2024</div>
							<div class="dots_list">
								<p>PDI센터 신규 증축 공사 준공(29,403㎡)</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2024.jpg') }}" alt="image"></div>
						</li>
						<li><div class="year">2023</div>
							<div class="dots_list">
								<p>평택항 항만배후부지 매입 (24,918 ㎡)</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2023.jpg') }}" alt="image"></div>
						</li>
					</ul>
				</div>
			
				<div class="box">
					<div class="point" id="year2010"></div>
					<ul>
						<li><div class="year">2019</div>
							<div class="dots_list">
								<p>항공기 급유차 9백만불 수출달성</p>
								<p>PLS 사업장<span class="roboto">內</span> 주차타워 건립</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2019.jpg') }}" alt="image"></div>
						</li>
						<li><div class="year">2016</div>
							<div class="dots_list">
								<p>특장차 제작/수출 사업 개시</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2016.jpg') }}" alt="image"></div>
						</li>
						<li><div class="year">2014</div>
							<div class="dots_list">
								<p>항만물류사업 개시</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2014.jpg') }}" alt="image"></div>
						</li>
						<li><div class="year">2011</div>
							<div class="dots_list">
								<p>평택항 자유무역지역內 신규 PDI 센터 오픈<br/>(부지-153,167㎡, 건축 -19,302.85㎡)</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2011.jpg') }}" alt="image"></div>
							<div class="dots_list">
								<p>PLS 본사 평택 이전</p>
							</div>
						</li>
					</ul>
				</div>
			
				<div class="box">
					<div class="point" id="year2000"></div>
					<ul>
						<li><div class="year">2009</div>
							<div class="dots_list">
								<p>GS글로벌 물류사업부 분할 PLS 설립 (인천항 자유무역지역)</p>
							</div>
						</li>
						<li><div class="year">2004</div>
							<div class="dots_list">
								<p>인천북항 수입자동차 제2 PDI 센터 오픈</p>
							</div>
							<div class="imgfit"><img src="{{ asset('images/img_history2004.jpg') }}" alt="image"></div>
						</li>
					</ul>
				</div>
			
				<div class="box">
					<div class="point" id="year1990"></div>
					<ul>
						<li><div class="year">1993</div>
							<div class="dots_list">
								<p>인천항 PDI 센터 오픈 (국내 최초)</p>
							</div>
						</li>
					</ul>
				</div>
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
            $(".years.year2020 li").removeClass("on");
            $(".years.year2020 li a[href='#" + id + "']")
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
