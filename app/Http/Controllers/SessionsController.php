<?php

namespace App\Http\Controllers;

use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionsController extends Controller
{
	use ThrottlesLogins;

	protected $lockoutTime = 60;

	protected $maxLoginAttempts = 5;

	public function __construct()
	{
		$this->middleware('guest', ['except' => 'destroy']);
	}

	public function create()
	{
		return view('sessions.create');
	}

	public function destroy()
	{
		auth()->logout();
		flash('또 방문해 주세요.');
		return redirect('/');
	}

	protected function respondCreated($token)
	{
		flash(trans('auth.sessions.info_welcome', ['name' => auth()->user()->name]));
		return ($return = \request('return')) ? redirect(urldecode($return)) : redirect()->intended();
	}

	protected function respondError($message)
	{
		flash()->error($message);
		return back()->withInput();
	}

	protected function respondLoginFailed()
	{
		flash()->error(trans('auth.sessions.error_incorrect_credentials'));
		return back()->withInput();
	}

	protected function respondNotConfirmed()
	{
		flash()->error(trans('auth.sessions.error_not_confirmed'));
		return back()->withInput();
	}

	protected function sendLockoutResponse(Request $request)
	{
		$seconds = app(RateLimiter::class)->availableIn($this->throttleKey($request));
		return json()->tooManyRequestsError('account_locked:for_{$seconds}_sec');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required|min:6',
		]);
		/* if(!(auth()->attempt($request->only('email', 'password'), $request->has('remember')))) {
			//flash('이메일 또는 비밀번호가 맞지 않습니다.');
			//return back()->withInput();
			return $this->respondError('이메일 또는 비밀번호가 맞지 않습니다.');
		}
		if(!(auth()->user()->activated)) {
			auth()->logout();
			flash('가입 확인해 주세요.');
		}
		flash(auth()->user()->name . '님, 환경합니다.');
		return redirect()->intended('home'); */

		$throttles = method_exists($this, 'hasTooManyLoginAttempts');
		if(($throttles) && ($lockedOut = $this->hasTooManyLoginAttempts($request))) {
			$this->fireLockoutEvent($request);
			return $this->sendLockoutResponse($request);
		}

		$token = is_api_domain()
			? jwt()->attempt($request->only('email', 'password'))
			: auth()->attempt($request->only('email', 'password'), $request->has('remember'));
		if(!($token)) {
			if(($throttles) && (!($lockedOut))) {
				$this->incrementLoginAttempts($request);
			}
			$this->respondLoginFailed();
		}
		if((!(auth()->user())) || (!(auth()->user()->activated))) {
			auth()->logout();
			return $this->respondNotConfirmed();
		}
		return $this->respondCreated($token);
	}

	public function username()
	{
		return 'email';
	}
}
