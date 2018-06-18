<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

	public function __construct()
	{
		$this->middleware('guest', ['only' => ['confirm', 'create', 'store']]);
	}

	public function confirm($code)
	{
		$user = \App\User::whereConfirmCode($code)->first();
		if(!($user)) {
			flash('URL이 정확하지 않습니다.');
			return redirect('/');
		}

		$user->activated = 1;
		$user->confirm_code = null;
		$user->save();

		auth()->login($user);
		flash(auth()->user()->name . '님 환영합니다. 가입 확인되었습니다.');

		return redirect('home');
	}

	public function create()
	{
		return view('users.create');
	}

	protected function createNativeAccount(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);

		$confirmCode = str_random(60);

		$user = \App\User::create([
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => bcrypt($request->input('password')),
			'confirm_code' => $confirmCode,
		]);

		event(new \App\Events\UserCreated($user));
		return $this->respondConfirmationEmailSend();
	}

	protected function respondConfirmationEmailSend()
	{
		flash(trans('auth.users.info_confirmation_send'));
		return redirect('/'); //(route('root'));
	}

	protected function respondSuccess(\App\User $user, $message = null)
	{
		auth()->login($user);
		flash($message);
		return ($return = \request('return')) ? redirect(urldecode($return)) : redirect()->intended();
	}

	protected function respondUpdated(\App\User $user)
	{
		return $this->respondSuccess( $user, trans('auth.users.info_welcome', ['name' => $user->name]) );
	}

	public function store(Request $request)
	{

		//$socialUser = \App\User::whereEmail($request->input('email'))->whereNull('password')->first();
		$socialUser = \App\User::socialUser($request->get('email'))->first();
		if($socialUser) {
			return $this->updateSocialAccount($request, $socialUser);
		}

		return $this->createNativeAccount($request);
	}

	/**
	 * @deprecated
	 */
	public function storeLegacy(Request $request)
	{
		//dd($request);
		$this->validate($request, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);

		$confirmCode = str_random(60);
		$user = \App\User::create([
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => bcrypt($request->input('password')),
			'confirm_code' => $confirmCode,
		]);

		/* auth()->login($user);
		flash(auth()->user()->name . '님, 환영합니다.');
		return redirect('home'); */

		/* Mail::send('emails.auth.confirm', compact('user'), function($message) use($user) {
			$message->to($user->email);
			$message->subject(sprintf('[%s] 회원 가입을 확인해 주세요.', config('app.name')));
		}); */
		event(new \App\Events\UserCreated($user));
		flash('가입하신 메일 계정으로 가입 확인 메일을 보내드렸습니다. 가입 확인하시고 로그인해 주세요.');
		return redirect('/');
	}

	protected function updateSocialAccount(Request $request, \App\User $user)
	{
		$this->validate($request, [
			'name' => 'required|max:255',
			//'email' => 'required|email|max:255|unique:users',
			'email' => 'required|email|max:255',
			'password' => 'required|confirmed|min:6',
		]);

		$user->update([
			'name' => $request->input('name'),
			'password' => bcrypt($request->input('password')),
		]);

		$this->respondUpdated($user);
	}
}
