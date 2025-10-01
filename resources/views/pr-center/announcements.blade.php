@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	<div class="inner">

		<div class="board_top">
			<div class="total">TOTAL<strong>{{ number_format($total) }}</strong></div>
			<form action="{{ route('pr-center.announcements') }}" method="GET" class="search_area">
				<select name="search_type" class="text">
					<option value="제목" {{ $searchType == '제목' ? 'selected' : '' }}>제목</option>
					<option value="내용" {{ $searchType == '내용' ? 'selected' : '' }}>내용</option>
				</select>
				<input type="text" name="search_keyword" class="text" placeholder="검색어를 입력해주세요." value="{{ $searchKeyword ?? '' }}">
				<button type="submit" class="btn">검색</button>
			</form>
		</div>

		<div class="tbl board_list">
			<table>
				<colgroup>
					<col class="w160"/>
					<col/>
					<col class="w160"/>
					<col class="w160"/>
				</colgroup>
				<thead>
					<tr>
						<th>NO</th>
						<th>제목</th>
						<th>조회수</th>
						<th>등록일</th>
					</tr>
				</thead>
				<tbody>
					@forelse($posts as $index => $post)
					<tr class="{{ $post->is_notice ? 'notice' : '' }} {{ $post->is_new ? 'new' : '' }}">
						<td class="num">
							@if($post->is_notice)
								<span>공지</span>
							@else
								{{ $posts->total() - $posts->firstItem() - $index + 1 }}
							@endif
						</td>
						<td class="tal"><a href="{{ route('pr-center.announcements.show', $post->id) }}">{{ $post->title }}</a></td>
						<td class="hit">{{ $post->view_count }}</td>
						<td class="date">{{ $post->formatted_date }}</td>
					</tr>
					@empty
					<tr>
						<td colspan="4" class="no_data">등록된 공지사항이 없습니다.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<div class="board_bottom">
			<div class="paging">
				@if($posts->onFirstPage())
					<a href="#" class="arrow two first disabled">맨처음</a>
					<a href="#" class="arrow one prev disabled">이전</a>
				@else
					<a href="{{ $posts->url(1) }}" class="arrow two first">맨처음</a>
					<a href="{{ $posts->previousPageUrl() }}" class="arrow one prev">이전</a>
				@endif

				@foreach(range(1, $posts->lastPage()) as $page)
					@if($page == $posts->currentPage())
						<a href="#" class="on">{{ $page }}</a>
					@else
						<a href="{{ $posts->url($page) }}">{{ $page }}</a>
					@endif
				@endforeach

				@if($posts->hasMorePages())
					<a href="{{ $posts->nextPageUrl() }}" class="arrow one next">다음</a>
					<a href="{{ $posts->url($posts->lastPage()) }}" class="arrow two last">맨끝</a>
				@else
					<a href="#" class="arrow one next disabled">다음</a>
					<a href="#" class="arrow two last disabled">맨끝</a>
				@endif
			</div>
		</div> <!-- //board_bottom -->
		
	</div>
</div> <!-- //container -->
@endsection
