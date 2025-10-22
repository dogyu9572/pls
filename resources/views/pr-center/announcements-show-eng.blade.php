@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	<div class="inner">

		<div class="board_view">
			<div class="view_head">
				<div class="tit">{{ $post->title }}</div>
				<div class="date">{{ $post->created_at->format('Y.m.d') }}</div>
			</div>
			<div class="view_body">
				{!! $post->content !!}
			</div>
			@if($post->attachments && count($post->attachments) > 0)
			<div class="view_files">
				@foreach($post->attachments as $file)
					<a href="{{ asset('storage/' . $file['path']) }}" download="{{ $file['name'] }}">{{ $file['name'] }}</a>
				@endforeach
			</div>
			@endif
			<div class="view_foot">
				@if($prevPost)
					<a href="{{ route('eng.pr-center.announcements.show', $prevPost->id) }}" class="arrow prev"><span>Previous</span>{{ $prevPost->title }}</a>
				@else
					<span class="arrow prev disabled"><span>Previous</span>No previous post.</span>
				@endif
				
				@if($nextPost)
					<a href="{{ route('eng.pr-center.announcements.show', $nextPost->id) }}" class="arrow next"><span>Next</span>{{ $nextPost->title }}</a>
				@else
					<span class="arrow next disabled"><span>Next</span>No next post.</span>
				@endif
				
				<a href="{{ route('eng.pr-center.announcements') }}" class="btn_list">List</a>
			</div>
		</div>
		
	</div>
</div> <!-- //container -->
@endsection
