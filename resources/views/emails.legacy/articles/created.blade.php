<h1>
	{{ $article->title }}
	<small>{{ $article->user->name }}</small>
</h1>
<hr />
<p>
	{{ $article->content }}
	<small>{{ $article->created_at }}</small>
</p>
<hr />

<div>
	<img src="{{ $message->embed(storage_path('img/1.jpg')) }}" />
</div>

<footer>
	이 메일은 {{ config('app.url') }} 에서 보냈습니다.
</footer>