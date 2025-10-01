@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	<div class="inner">

		<div class="board_top">
			<div class="total">TOTAL<strong>0</strong></div>
			<div class="search_area">
				<select name="" id="" class="text">
					<option value="">제목</option>
					<option value="">내용</option>
				</select>
				<input type="text" class="text" placeholder="검색어를 입력해주세요.">
				<button type="submit" class="btn">검색</button>
			</div>
		</div>

		<div class="gallery_list">
			<div class="no_data">등록된 소식이 없습니다.</div>
		</div>

		<div class="board_bottom">
			<div class="paging">
				<a href="#this" class="arrow two first">맨끝</a>
				<a href="#this" class="arrow one prev">이전</a>
				<a href="#this" class="on">1</a>
				<a href="#this" class="arrow one next">다음</a>
				<a href="#this" class="arrow two last">맨끝</a>
			</div>
		</div> <!-- //board_bottom -->

	</div>
</div> <!-- //container -->
@endsection
