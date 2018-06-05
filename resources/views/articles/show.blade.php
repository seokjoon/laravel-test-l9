@extends('layouts.app')

@section('content')
	@php $viewName = 'articles.show'; @endphp
	<div class="page-header">
		<h4>포럼<small> / {{ $article->title }}</small></h4>
	</div>

	<div class="col-md-9 list__article">
		<article data-id="{{ $article->id }}" id="item__article">
			@include('articles.partial.article', compact('article'))
			<div class="content__article"> {!! markdown($article->content) !!} </div>
			@include('tags.partial.list', ['tags' => $article->tags])
		</article>
	</div>

	<!-- div class="text-center action__article">
		<a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
			<i class="fa fa-pencil"></i>글 수정
		</a>
		<button class="btn btn-danger button__delete">
			<i class="fa fa-trash-o"></i>글 삭제
		</button>
		<a href="{{ route('articles.index') }}" class="btn btn-default">
			<i class="fa fa-list"></i>글 목록
		</a>
	</div -->

	<div class="text-center action__article">
		<form action="{{ route('articles.destroy', $article->id) }}" method="post">
			@can('delete', $article)
			{!! csrf_field() !!}
			{!! method_field('DELETE') !!}
			<button type="submit" class="btn btn-danger">
				<i class="fa fa-trash-o"></i>글 삭제
			</button>
			@endcan
			@can('update', $article)
			<a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
				<i class="fa fa-pencil"></i>글 수정
			</a>
			@endcan()
			<a href="{{ route('articles.index') }}" class="btn btn-default">
				<i class="fa fa-list"></i>글 목록
			</a>
		</form>
	</div>
@stop

{{-- @section('script')
	<script>
		jQuery.ajaxSetup({
			headers: { "X-CSRF-TOKEN": jQuery("meta[name='csrf-token']").attr("content") }
		});
		jQuery(".button__delete").on("click", function(e) {
			var articleId = jQuery("article").data("id"); //console.log(articleId);
			//var articleId = "{{ $article->id }}";
			if(confirm("글을 삭제합니다.")) {
				jQuery.ajax({
					type: "DELETE",
					url: "/articles/" + articleId,
					success: function(out) { console.log("success"); console.log(out); },
					failure: function(out) { console.log("failure"); console.log(out); },
					error: function(out) { console.log("error"); console.log(JSON.stringify(out)); },
				}).then(function() {
					window.location.href="/articles";
				});
			}
		});
	</script>
@stop --}}