<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\Http\Controllers\ArticlesController as ParentController;
use App\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{

	protected function respondCreate(Article $article)
	{
		return response()->json(
			['success' => 'created'],
			201,
			['location' => '생성한_리소스의_상세보기_API_엔드포인트'],
			JSON_PRETTY_PRINT
		);
	}

	protected function respondCollection(LengthAwarePaginator $articles)
	{
		//return $articles->toJson(JSON_PRETTY_PRINT);
		return response()->json($articles, 200, [], JSON_PRETTY_PRINT);
	}

	public function tags()
	{
		return Tag::all();
	}
}