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

Route::get('/', ['as' => 'home', function () { return view('welcome'); }]);
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
