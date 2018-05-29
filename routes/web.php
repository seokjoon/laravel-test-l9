<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('welcome'); });
//Route::get('/', ['as' => 'home', function () { return view('welcome'); }]);
//Route::get('/home', function () { return redirect(route('home')); });
//Route::get('/{foo?}', function($foo = 'bar') { return $foo; })->where('foo', '[0-9a-zA-Z]{3}');
//Route::get('/error', ['error' => 'error', function() { return view('errors.503'); }]);
//Route::get('/errorT1', function() { return redirect('error'); });
//Route::get('/t1', function() { return view('t1')->with('name', 'Foo'); });
//Route::get('/t1', function() { return view('t1')->with(['name' => 'Foo', 'greeting' => 'Bar']); });
Route::get('/t1',
	function() {
		$items = ['apple', 'banana', 'tomato'];
		return view('t1', ['items' => $items, 'name' => 'Foo', 'greeting' => 'Bar']);
	}
);
Route::get('/t2', 'T2Controller@index');

Route::get('auth/login', function() {
	$cds = [ 'email' => 'a@b.c', 'password' => '11111111', ];
	if(!(auth()->attempt($cds))) { return 'incorrect login info'; }
	return redirect('protected');
});
Route::get('protected', function(){
	dump(session()->all());
	if(!(auth()->check())) { return 'who?'; }
	return 'welcome' . auth()->user()->name;
});
Route::get('protected', ['middleware' => 'auth', function(){
	dump(session()->all());
	return 'welcome' . auth()->user()->name;
}]);
Route::get('auth/logout', function() {
	auth()->logout();
	return 'see again';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('articles', 'ArticlesController');
//DB::listen(function($query) { dump($query->sql); });

Route::get('mail', function() {
	$article = App\Article::with('user')->find(1); //dd($article);
	return Mail::send(
		'emails.articles.created',
		compact('article'),
		function($message) use($article) {
			$message->to('seokjoon@gmail.com');
			$message->subject('새 글이 등록되었습니다 - ' . $article->title);
			$message->attach(storage_path('img/1.jpg'));
		}
	);
});

Route::get('markdown', function () {
	$text =<<<EOT
# test
- test test
EOT;
	return app(ParsedownExtra::class)->text($text);
});

/* Route::get('docs/{file?}', function($file = 'documentation') {
	$text = (new App\Documentation)->get($file);
	return app(ParsedownExtra::class)->text($text);
}); */
Route::get('docs/{file?}', 'DocsController@show');
//Route::get('docs/images/{image}', 'DocsController@image')->where('image', '[\pL-\pN._-]+-img-[0-9]{2}.jpg]');
Route::get('docs/images/{image}', 'DocsController@image');
