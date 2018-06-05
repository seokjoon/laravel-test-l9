@extends('layouts.app')

@section('content')
	@php $viewName = 'articles.show'; @endphp

	<div class="page-header">
		<h4>
			<a href="{{ route('articles.index') }}">
				{{ trans('forum.title') }}
			</a>
			<small>/ {{ $article->title }}</small>
		</h4>
	</div>

	<div class="row container__article">
		<div class="col-md-3 sidebar__article">
		</div>
	</div>

	<div class="col-md-9 list__article">
		<article data-id="{{ $article->id }}" id="item__article">
			<div class="content__article">
				{{-- {!! app(ParsedownExtra::class)->text($article->content) !!} --}}
				{!! markdown($article->content) !!}
			</div>
		</article>
		<div class="text-center action__article">
		</div>
	</div>
@stop