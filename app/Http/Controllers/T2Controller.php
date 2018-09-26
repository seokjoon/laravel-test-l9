<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class T2Controller extends Controller
{

	public function index()
	{
		//return view('t2', ['foo' => 'bar']);
		return view('t2', ['foo' => Article::first()]);
	}
}
