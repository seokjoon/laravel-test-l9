<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{

	public function locale()
	{
		$cookie = cookie()->forever('locale__l9', request('locale'));
		cookie()->queue($cookie);
		return ($return = request('return')) ? redirect(urlencode($return))->withCookie($cookie) : redirect('/')->withCookie($cookie);
	}
}
