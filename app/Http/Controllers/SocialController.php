<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

	public function __construct()
	{
		$this->middleware('guest');
	}

	public function excute(Request $request, $provider)
	{
		//dump($provider);
		//dd($request);

		if(!($request->has('code'))) {
			return $this->redirectToProvider($provider);
		}
		return $this->handleProviderCallback($provider);
	}

	public function handleProviderCallback($provider)
	{
		//dd($provider);

		$user = Socialite::driver($provider)->user();
		dd($user);
	}

	protected function redirectToProvider($provider)
	{
		return Socialite::driver($provider)->redirect();
	}
}