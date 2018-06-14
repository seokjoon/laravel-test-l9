<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class WelcomeController extends Controller
{
	public function index()
	{
		$urlBase = request()->getBaseUrl();

		return response()->json([
			'name' => config('app.name') . ' API',
			'message' => 'This is a base endpoint of v1 API.',
			'links' => [
				['rel' => 'self', 'href' => route(Route::currentRouteName())],
				['rel' => 'api.v1.articles', 'href' => route('api.v1.articles.index')],
				['rel' => 't1', 'href' => route('articles.index')],
			],
		], 200, [], JSON_PRETTY_PRINT);
	}
}