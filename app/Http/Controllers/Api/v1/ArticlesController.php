<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\Http\Controllers\ArticlesController as ParentController;
use App\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{

	public function __construct()
	{
		parent::__construct();
		$this->middleware = [];
		//$this->middleware('auth.basic.once', ['except' => ['index', 'show', 'tags']]);
		$this->middleware('jwt.auth', ['except' => ['index', 'show', 'tags']]);
	}

	protected function respondCreate(Article $article)
	{
		/* return response()->json(
			['success' => 'created'],
			201,
			['location' => '생성한_리소스의_상세보기_API_엔드포인트'],
			JSON_PRETTY_PRINT
		); */
		return json()->setHeaders(['Location' => route('api.v1.articles.show', $article->id)])->created('created');
	}

	protected function respondCollection(LengthAwarePaginator $articles)
	{
		//return $articles->toJson(JSON_PRETTY_PRINT);
		//return response()->json($articles, 200, [], JSON_PRETTY_PRINT);
		//return (new \App\Transformers\ArticleTransformerBasic)->withPagination($articles);
		return json()->withPagination($articles, new \App\Transformers\ArticleTransformer);
	}

	protected function respondInstance(\App\Article $article, $comments)
	{
		//return $article->toJson(JSON_PRETTY_PRINT);
		//return (new \App\Transformers\ArticleTransformerBasic)->withItem($article);
		return json()->withItem($article, new \App\Transformers\ArticleTransformer);
	}

	protected function respondUpdate(\App\Article $article)
	{
		//return response()->json(['success' => 'updated'], 200, [], JSON_PRETTY_PRINT);
		return json()->success('updated');
	}

	public function tags()
	{
		//return Tag::all();
		return json()->withCollection(\App\Tag::all(), new \App\Transformers\TagTransformer);

	}
}