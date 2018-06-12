<p class="lead"><li class="fa fa-tags"></li>태그</p>

<ul class="list-unstyled">
	@foreach($allTags as $tag)
		<li {!! str_contains(request()->path(), $tag->slug) ? 'class="active"' : '' !!}>
			<a href="{{ route('tags.articles.index', $tag->slug) }}"> {{ $tag->{$currentLocale} }} </a>
			@if($count = $tag->articles->count())
				<span class="badge badge-info">{{ $count }}</span>
			@endif
		</li>
	@endforeach
</ul>