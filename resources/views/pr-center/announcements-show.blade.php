@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }} no_aside">
	<div class="inner">

		<div class="board_view">
			<div class="view_head">
				<div class="tit">{{ $post->title }}</div>
				<div class="date">{{ $post->formatted_date }}</div>
			</div>
			<div class="view_body">
				{!! $post->content !!}
			</div>
			@if($post->attachments && count($post->attachments) > 0)
			<div class="view_files">
				@foreach($post->attachments as $file)
					<a href="{{ asset('storage/' . $file['path']) }}" download>{{ $file['name'] }}</a>
				@endforeach
			</div>
			@endif
			<div class="view_foot">
				@if($prevPost)
					<a href="{{ route('pr-center.announcements.show', $prevPost->id) }}" class="arrow prev"><span>이전글</span>{{ $prevPost->title }}</a>
				@else
					<span class="arrow prev disabled"><span>이전글</span>이전글이 없습니다.</span>
				@endif
				
				@if($nextPost)
					<a href="{{ route('pr-center.announcements.show', $nextPost->id) }}" class="arrow next"><span>다음글</span>{{ $nextPost->title }}</a>
				@else
					<span class="arrow next disabled"><span>다음글</span>다음글이 없습니다.</span>
				@endif
				
				<a href="{{ route('pr-center.announcements') }}" class="btn_list">목록</a>
			</div>
		</div>
		
	</div>
</div> <!-- //container -->
@endsection
