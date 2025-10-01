@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} pb0">
	
	<div class="sub_visual recruitment_information_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">(주)피엘에스와 <br class="mo_vw"/>함께 성장할 <br class="pc_vw"/><strong>열정</strong>을 <br class="mo_vw"/>가진 분들을 기다립니다. </div>
			<p>당신의 도전과 꿈이 <br class="mo_vw"/>우리의 미래가 됩니다.</p>
		</div>
	</div>

	<div class="information01">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Recruitment Process</p><strong>채용절차</strong></div>
				<div class="con">
					<ul class="recruitment_process">
						<li class="i1"><p>STEP 01</p><strong>지원자 접수</strong></li>
						<li class="i2"><p>STEP 02</p><strong>서류전형</strong></li>
						<li class="i3"><p>STEP 03</p><strong>면접</strong></li>
						<li class="i4"><p>STEP 04</p><strong>최종합격</strong></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="information2 sub_padding">
		<div class="inner">
			<div class="dl_area">
				<div class="stit"><p>Open Positions</p><strong>채용대상</strong></div>
				<div class="con">
					<ul class="open_positions">
						<li class="i1">
							<div class="tit">경영지원사무직</div>
							<div class="tb">기획, 자금, 회계, 인사, 총무, 안전/보건</div>
							<div class="dots_list">
								<p>대졸이상 지원자</p>
								<p>관련 학과 / 경력 우대</p>
							</div>
						</li>
						<li class="i2">
							<div class="tit">영업관리직</div>
							<div class="tb">PDI, 항만물류, 특장</div>
							<div class="dots_list">
								<p>대졸이상 지원자</p>
								<p>관련 학과 / 경력 우대</p>
							</div>
						</li>
						<li class="i3">
							<div class="tit">PDI 기능직</div>
							<div class="dots_list">
								<p>고졸이상 지원자</p>
								<p>자동차 관련 학과 / 경력 / 자격증 우대</p>
							</div>
						</li>
						<li class="i4">
							<div class="tit">도장 기능직</div>
							<div class="dots_list">
								<p>고졸이상 지원자</p>
								<p>도장 관련 학과 / 경력 / 자격증 우대</p>
							</div>
						</li>
						<li class="i5">
							<div class="tit">AS정비 기능직</div>
							<div class="dots_list">
								<p>고졸이상 지원자</p>
								<p>EV자동차 학과 / 경력 / 자격증 우대</p>
							</div>
						</li>
						<li class="i6">
							<div class="tit">창의적 사고와 열정으로 <br/>미래를 열어갈 <br/>인재를 채용합니다.</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="business_contact sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Careers Contact</p><strong>채용문의</strong><a href="#this" class="btn_link flex_center">채용공고</a></div>
			<ul>
				<li class="i1"><div class="tt">TEL</div><p><strong>031-684-9665</strong></p></li>
				<li class="i2"><div class="tt">MAIL</div><p><strong>shpark@plscorp.co.kr</strong></p></li>
			</ul>
		</div>
	</div>

</div> <!-- //container -->
@endsection
