@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	<div class="inner">

		<div class="board_top">
			<div class="total">TOTAL<strong>{{ number_format($total) }}</strong></div>
			<form action="{{ route('eng.pr-center.news') }}" method="GET" class="search_area">
				<select name="search_type" class="text">
					<option value="Title" {{ $searchType == 'Title' ? 'selected' : '' }}>Title</option>
					<option value="Content" {{ $searchType == 'Content' ? 'selected' : '' }}>Content</option>
				</select>
				<input type="text" name="search_keyword" class="text" placeholder="Please enter search keyword." value="{{ $searchKeyword ?? '' }}">
				<button type="submit" class="btn">Search</button>
			</form>
		</div>

		<div class="gallery_list">
			@forelse($posts as $post)
			<a href="{{ route('eng.pr-center.news.show', $post->id) }}" class="{{ $post->is_new ? 'new' : '' }}">
				<span class="imgfit">
					<img src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('images/default.jpg') }}" alt="{{ $post->title }}">
				</span>
				<span class="txt">
					<p>{{ Str::limit($post->title, 50) }}</p>
					<span class="date">{{ $post->formatted_date }}</span>
				</span>
			</a>
			@empty
			<div class="no_data">No registered news.</div>
			@endforelse
		</div>

		<div class="board_bottom">
			<div class="paging">
				@if($posts->onFirstPage())
					<a href="#" class="arrow two first disabled">First</a>
					<a href="#" class="arrow one prev disabled">Prev</a>
				@else
					<a href="{{ $posts->url(1) }}" class="arrow two first">First</a>
					<a href="{{ $posts->previousPageUrl() }}" class="arrow one prev">Prev</a>
				@endif

				@foreach(range(1, $posts->lastPage()) as $page)
					@if($page == $posts->currentPage())
						<a href="#" class="on">{{ $page }}</a>
					@else
						<a href="{{ $posts->url($page) }}">{{ $page }}</a>
					@endif
				@endforeach

				@if($posts->hasMorePages())
					<a href="{{ $posts->nextPageUrl() }}" class="arrow one next">Next</a>
					<a href="{{ $posts->url($posts->lastPage()) }}" class="arrow two last">Last</a>
				@else
					<a href="#" class="arrow one next disabled">Next</a>
					<a href="#" class="arrow two last disabled">Last</a>
				@endif
			</div>
		</div> <!-- //board_bottom -->

	</div>
</div> <!-- //container -->
@endsection
