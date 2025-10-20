@extends('layouts.app_eng')

@section('content')
<div id="mainContent">

	<div class="mvisual_wrap">
		<div class="mvisual">
			@foreach($banners as $banner)
				@if($banner->banner_type === 'image')
					<div class="banner-slide">
						<img src="{{ asset('storage/' . $banner->desktop_image) }}" 
							 alt="{{ $banner->title }}" 
							 class="banner-desktop-image">
						@if($banner->mobile_image)
							<img src="{{ asset('storage/' . $banner->mobile_image) }}" 
								 alt="{{ $banner->title }}" 
								 class="banner-mobile-image">
						@endif
					</div>
				@elseif($banner->banner_type === 'video')
					<div class="banner-slide video">
						<video width="100%" height="100%" autoplay muted 
							   data-duration="{{ $banner->video_duration ?? 5 }}">
							<source src="{{ asset('storage/' . $banner->video_file) }}" type="video/mp4">
							해당 브라우저에서는 영상이 나오지 않습니다.
						</video>
					</div>
				@endif
			@endforeach
		</div>
		<div class="abso_box">
			<div class="mvisual_txt">
				@foreach($banners as $banner)
					<div class="box">
						@if($banner->sub_text)
							<p>{{ $banner->sub_text }}</p>
						@endif
						<div class="tit">
							@if($banner->main_text)
								<strong>{{ $banner->main_text }}</strong>
							@endif
							@if($banner->sub_text2)
								<span>{{ $banner->sub_text2 }}</span>
							@endif
						</div>
					</div>
				@endforeach
			</div>
			<div class="navi">
				<div class="paging"></div>
				<button type="button" class="arrow prev">이전</button>
				<button type="button" class="arrow next">다음</button>
				<button type="button" class="papl pause on">정지</button>
				<button type="button" class="papl play">재생</button>
			</div>
		</div>
		<div class="scroll"><i></i><span>SCROLL DOWN</span></div>
	</div>

	<div class="mcon_point_area">
		<div class="point" id="point_mc01"></div>
		<div class="point" id="point_mc01_mid"></div>
	</div>
	<div class="mcon mcon01">
		<div class="positionbox">
			<div class="inner">
				<div class="mtit"><span>Business</span><div class="tit">PLS는 <strong>수입자동차 PDI 사업, 항만물류사업, <br/>특장차 제조업</strong>을 영위하고 있습니다.</div></div>
				<div class="flex before">
					<div class="box i2"><div class="inbox"><div class="tit">항만물류사업</div></div></div>
					<div class="box i1"><div class="inbox"><div class="tit">수입자동차 PDI 사업</div></div></div>
					<div class="box i3"><div class="inbox"><div class="tit">특장차 제조사업</div></div></div>
				</div>
			</div>
		</div>
	</div>
	<div class="mcon_point_area">
		<div class="point" id="point_mc02"></div>
	</div>
	<div class="mcon mcon02">
		<div class="positionbox side_motion">
			<div class="box i1">
				<div class="inbox">
					<div class="inner">
						<div class="txt">
							<div class="cate">Business</div>
							<div class="tit">수입자동차 PDI 사업</div>
							<p>국내 수입자동차 PDI 선도기업으로 평택항 자유무역지역의 자동차 물류 인프라와 <br class="pc_vw">자체 개발한 PDI 운영관리 플랫폼을 기반으로 차량 진단 및 점검부터 보관·운송까지 <br class="pc_vw">One-Stop PDI Service를 제공하고 있습니다.</p>
							<a href="{{ route('business.imported-automobiles') }}" class="btn_more">VIEW MORE</a>
						</div>
					</div>
				</div>
			</div>
			<div class="box i2">
				<div class="inbox">
					<div class="inner">
						<div class="txt">
							<div class="cate">Business</div>
							<div class="tit">항만물류사업</div>
							<p>평택항을 거점으로 GS그룹 발전사에 납품되는 Bio-mass 발전연료에 대한 <br class="pc_vw">항만물류업무(하역·보관·운송)를 수행하고 있습니다.</p>
							<a href="{{ route('business.port-logistics') }}" class="btn_more">VIEW MORE</a>
						</div>
					</div>
				</div>
			</div>
			<div class="box i3">
				<div class="inbox">
					<div class="inner">
						<div class="txt">
							<div class="cate">Business</div>
							<div class="tit">특장차 제조사업</div>
							<p>2016년 중동 지역에 항공기 급유차를 수출한 것을 시작으로, <br class="pc_vw">지속적인 신규 시장 개척을 통해 글로벌 경쟁력을 확대해 나가고자 합니다.</p>
							<a href="{{ route('business.special-vehicle') }}" class="btn_more">VIEW MORE</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mcon_point_area">
		<div class="point" id="point_mc02_end"></div>
	</div>

	<div class="mcon mcon03 slide_arrow_type1">
		<div class="inner">
			<div class="mtit"><span>News</span><div class="tit">PLS의 다양한 소식을 <br/>빠르게 전달드립니다.</div><a href="{{ route('pr-center.news') }}" class="btn_more line">VIEW MORE</a></div>
			<div class="mc03_slide slide_area">
				@forelse($galleryPosts as $post)
				<a href="{{ $post->url }}" class="box">
					<span class="imgfit">
						<img src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('images/default.jpg') }}" alt="{{ $post->title }}">
					</span>
					<span class="txt">
						<p>{{ $post->title }}</p>
						<span class="date">{{ \Carbon\Carbon::parse($post->created_at)->format('Y.m.d') }}</span>
					</span>
				</a>
				@empty
				<a href="#" class="box">
					<span class="imgfit"></span>
					<span class="txt">
						<p>등록된 소식이 없습니다.</p>
						<span class="date">{{ date('Y.m.d') }}</span>
					</span>
				</a>
				@endforelse
			</div>
			<div class="navi navi_type1 flex_center">
				<div class="paging"></div>
				<button class="papl pause on">정지</button>
				<button class="papl play">재생</button>
			</div>
		</div>
	</div>

	<div class="mcon mcon04">
		<div class="inner">
			<div class="mtit"><span>News</span><div class="tit">PLS의 <strong>공지사항</strong>을 <br/>확인하세요.</div><a href="{{ route('pr-center.announcements') }}" class="btn_more line">VIEW MORE</a></div>
			<div class="list">
				@forelse($noticePosts as $post)
				<a href="{{ $post->url }}"><p>{{ $post->title }}</p><span class="date">{{ \Carbon\Carbon::parse($post->created_at)->format('Y.m.d') }}</span></a>
				@empty
				<a href="#"><p>등록된 공지사항이 없습니다.</p><span class="date">{{ date('Y.m.d') }}</span></a>
				@endforelse
			</div>
		</div>
	</div>

	<div class="mcon mcon05">
		<div class="bgbox bg1">
			<a href="/recruitment/information" class="flex_center colm btn1"><span class="tit">채용</span><p>개개인의 무한한 가능성을 이끌어내어 <br/>꿈을 현실로 이루는 피엘에스에서  우수한 인재를 모집합니다.<br/>(주)피엘에스는 언제나 여러분을 향해 열려 있습니다.</p><i class="btn_more">VIEW MORE</i></a>
			<a href="/contact-us" class="flex_center colm btn2"><span class="tit">사업문의</span><p>물류 혁신, 함께 만드는 가치 <br/>체계적인 운영과 스마트 물류 플랫폼으로 <br/>미래 물류를 선도합니다.</p><i class="btn_more">VIEW MORE</i></a>
		</div>
	</div>

</div>

<link rel="stylesheet" href="{{ asset('css/slick.css') }}" media="all">
<script src="{{ asset('js/slick.js') }}"></script>

<script type="text/javascript">
//<![CDATA[
$(document).ready (function () {
//mvisual
	$(".mvisual").on("init", function () {
		// 초기화 시 첫 번째 슬라이드 비디오 재생
		playCurrentVideo(0);
	}).on("afterChange", function (event, slick, currentSlide) {
		// 현재 슬라이드 영상 재생
		playCurrentVideo(currentSlide);
	}).slick({
		arrows: true,
		dots: true,
		autoplay: false, // 자동 재생 끔
		pauseOnHover: false,
		swipeToSlide: true,
		prevArrow: '.mvisual_wrap .prev',
		nextArrow: '.mvisual_wrap .next',
		appendDots: '.mvisual_wrap .paging',
		asNavFor: '.mvisual_txt',
		customPaging: function (slider, i) {
			return '<strong>' + (i + 1).toString().padStart(2, '0') + '</strong>/<span>' + slider.slideCount.toString().padStart(2, '0') + '<span class="line"><i></i></span></span>';
		}
	});

	// 텍스트 슬라이드
	$(".mvisual_txt").slick({
		arrows: false,
		dots: false,
		fade: true,
		asNavFor: '.mvisual'
	});

	// 재생/정지 버튼
	$('.mvisual_wrap .play').click(function () {
		$(".mvisual_wrap").removeClass("mv_pause");
		playCurrentVideo($('.mvisual').slick('slickCurrentSlide'));
		$(this).removeClass("on").siblings(".papl").addClass("on");
	});

	$('.mvisual_wrap .pause').click(function () {
		$(".mvisual_wrap").addClass("mv_pause");
		$('.mvisual video').each(function () { this.pause(); });
		$(this).removeClass("on").siblings(".papl").addClass("on");
	});

	// 현재 슬라이드 영상 재생 함수
	function playCurrentVideo(currentSlide) {
		$(".mvisual video").each(function () {
			this.pause();
			this.currentTime = 0;
		});

		const currentVideo = $(".mvisual .slick-slide[data-slick-index='" + currentSlide + "'] video").get(0);
		const $currentDotLine = $(".abso_box .navi .slick-dots li").eq(currentSlide).find(".line i");

		// 이전 타이머 제거 → 영상 끝나기 전에 자동으로 넘어가는 문제 방지
		clearTimeout(window.slideTimer);
		$(".abso_box .navi .slick-dots .line i").css("animation", "none");

		// 애니메이션 시작 함수
		function startDotAnimation(duration) {
			$currentDotLine.css("animation", `mvisual_paging ${duration}s linear forwards`);
		}

		if (currentVideo) {
			currentVideo.play().catch(() => {});

			const startVideoAnimation = () => {
				// data-duration 속성에서 설정된 재생 시간 사용
				const customDuration = currentVideo.getAttribute('data-duration');
				const duration = customDuration ? parseInt(customDuration) : (currentVideo.duration || 5);
				startDotAnimation(duration);
			};

			// 메타데이터가 로드되면 길이 확인 후 애니메이션 시작
			if (currentVideo.readyState >= 1) {
				startVideoAnimation();
			} else {
				currentVideo.onloadedmetadata = startVideoAnimation;
			}

			// 영상 끝나면 다음 슬라이드 (설정된 시간이 영상 길이보다 짧으면 강제로 넘어감)
			const customDuration = currentVideo.getAttribute('data-duration');
			if (customDuration) {
				const duration = parseInt(customDuration);
				window.slideTimer = setTimeout(() => {
					if (!$(".mvisual_wrap").hasClass("mv_pause")) {
						$(".mvisual").slick('slickNext');
					}
				}, duration * 1000);
			}
			
			currentVideo.onended = () => {
				if (!$(".mvisual_wrap").hasClass("mv_pause")) {
					$(".mvisual").slick('slickNext');
				}
			};
		} else {
			// 영상이 없는 슬라이드 → 5초 뒤 자동 이동
			startDotAnimation(5);
			window.slideTimer = setTimeout(() => {
				if (!$(".mvisual_wrap").hasClass("mv_pause")) {
					$(".mvisual").slick('slickNext');
				}
			}, 5000);
		}
	}

//mcon01
	$(window).on("scroll resize", function() {
		let scrollTop = $(window).scrollTop();
		let winH = $(window).height();
		let winCenter = scrollTop + winH / 1.2;

		// ====== flex.before 기반 progress 계산 ======
		let flex = $(".flex.before");
		if(flex.length){
			let flexTop = flex.offset().top;
			let flexH = flex.outerHeight();
			let flexCenter = flexTop + flexH / 2;
			let distance = flexCenter - winCenter;
			let maxDist = winH / 2;
			var progressFlex = Math.min(Math.max(distance / maxDist, 0), 1); // 0~1
		}

		// ====== mcon01 .box transform ======
		$(".mcon01 .box").each(function() {
			let box = $(this);
			let speed = 1.0;
			if (box.hasClass("i1")) speed = 0.5;
			if (box.hasClass("i2")) speed = 1.0;
			if (box.hasClass("i3")) speed = 1.5;
			let moveY = 40 * (progressFlex || 0) * speed; // progressFlex 없으면 0
			box.css("transform", "translateY(" + moveY + "vh)");
		});

		// mtit 애니메이션
		let mtit = $(".mcon01 .mtit");
		let mtitSpeed = 0.3;
		let mtitMoveY = 40 * (progressFlex || 0) * mtitSpeed;
		mtit.css("transform", "translateY(" + mtitMoveY + "vh)");

		// ====== fixed / mid 클래스 toggle ======
		let point1 = $('#point_mc01').offset().top;
		let pointMid = $('#point_mc01_mid').offset().top;

		if(scrollTop > point1){
			$('.mcon01').addClass("fixed").removeClass("end");
		} else {
			$('.mcon01').removeClass("fixed");
		}

		if(scrollTop > pointMid){
			$('.mcon01').addClass("mid").removeClass("end");
		} else {
			$('.mcon01').removeClass("mid");
		}

		// ====== inbox / tit 애니메이션 ======
		let inbox = $('.mcon01 .before .i1 .inbox');
		let tit = $('.mcon01 .before .box .tit');

		let range = 500;
		let progress = (scrollTop - pointMid) / range;
		progress = Math.min(Math.max(progress, 0), 1);

		if(progress > 0 && inbox.length){
			if (!inbox.data("initW")) {
				inbox.data("initW", inbox.outerWidth());
				inbox.data("initH", inbox.outerHeight());
				// border-radius는 CSS 값 읽어서 숫자만 추출
				let radius = parseInt(inbox.css("border-radius")) || 0;
				inbox.data("initRadius", radius);
			}

			let initW = inbox.data("initW");
			let initH = inbox.data("initH");
			let initRadius = inbox.data("initRadius");

			let targetW = window.innerWidth;
			let targetH = window.innerHeight;

			// width/height 계산
			let newW = initW + (targetW - initW) * progress;
			let newH = initH + (targetH - initH) * progress;

			// 최대값 도달 여부 확인
			if(newW >= targetW) newW = targetW;
			if(newH >= targetH) newH = targetH;

			let newRadius = initRadius * (1 - progress);
			let newOpacity = 1 - progress;

			// transform 계산
			let inboxCenter = inbox.offset().top + inbox.outerHeight()/2;
			let winCenterFixed = scrollTop + winH / 2;
			let fixedTop = $('.mcon01.fixed .positionbox').length ? 100 : 0;
			let startOffset = inboxCenter - winCenterFixed;

			// width/height가 최대값이면 transform도 멈춤
			let moveUp;
			if(newW === targetW && newH === targetH){
				// 최초 멈춘 시점 값을 저장
				if(!inbox.data('moveUpStopped')){
					let stoppedVal = startOffset + fixedTop*1.85 - 15;
					// 0보다 작은 값은 0으로, 정수로 내림
					stoppedVal = Math.max(0, Math.floor(stoppedVal));
					inbox.data('moveUpStopped', stoppedVal);
				}
				moveUp = inbox.data('moveUpStopped');
			} else {
				moveUp = startOffset * progress + fixedTop * progress * 1.85 - 15 * progress;
			}

			inbox.css({ position: 'fixed', top: '50%', left: '50%', width: newW + 'px', height: newH + 'px', transform: `translate(-50%, calc(-50% + ${-moveUp}px))`, 'border-radius': newRadius + 'px', 'z-index': 9999 });
			tit.css({ opacity: newOpacity });
		} else if(inbox.length){
			// 초기 상태 복원
			inbox.css({ position: '', top: '', left: '', width: '', height: '', transform: '', 'border-radius': '', 'z-index': '' });
			tit.css({ opacity: '' });
			inbox.removeData('moveUpStopped'); // 초기화
		}
	}).trigger("scroll");
//mcon02
	$(window).on("scroll", function() {
		let scrollTop = $(window).scrollTop();
		let winHeight = $(window).height();

		// 기준 요소들
		let pointStart = $("#point_mc02").offset().top;
		let pointEnd = $("#point_mc02_end").offset().top;

		// .mcon02 처리
		if (scrollTop >= pointStart && scrollTop < pointEnd) {
			$(".mcon01").addClass("fixed mid");
			$(".mcon02").addClass("start").removeClass("end");
		} else if (scrollTop >= pointEnd) {
			$(".mcon01").removeClass("fixed mid");
			$(".mcon02").addClass("end").removeClass("start");
		} else {
			$(".mcon02").removeClass("start end");
		}

		// 각 .box 처리
		$(".mcon02 .box").each(function() {
			let boxTop = $(this).offset().top;
			if (scrollTop >= boxTop) {
				$(this).addClass("start");
			} else {
				$(this).removeClass("start");
			}
		});
	});
//mcon03
	$(".mc03_slide").slick({
		arrows: true,
		dots: true,
		autoplay: true,
		autoplaySpeed: 5000,
		slidesToShow: 3,
		slidesToScroll: 3,
		swipeToSlide: true,
		appendDots: '.mcon03 .paging',
		responsive: [
			{
				breakpoint: 1440,
				settings: {
					arrows: false,
				}
			},
			{
				breakpoint: 767,
				settings: {
					arrows: false,
					slidesToShow: 1,
					slidesToScroll: 1,
				}
			},
		]
	});
	$('.mcon03 .play').click(function(){
		$('.mc03_slide').slick('slickPlay');
		$(this).removeClass("on").siblings(".papl").addClass("on");
	}); 
	$('.mcon03 .pause').click(function(){
		$('.mc03_slide').slick('slickPause');
		$(this).removeClass("on").siblings(".papl").addClass("on");
	});
//mcon05
	$(".mcon05 a.btn1").hover(function(){
		$(".mcon05 .bgbox").addClass("bg1").removeClass("bg2");
	});
	$(".mcon05 a.btn2").hover(function(){
		$(".mcon05 .bgbox").addClass("bg2").removeClass("bg1");
	});
});
//]]>
</script>
@endsection