<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UsersEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
    	$event->user->last_login = Carbon::now();
		$event->user->save();
    }

    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event)
	{
		Mail::send('emails.' . app()->getLocale() . '.passwords.reset',
			['token' => $event->token],
			function($message) use($event) {
				$message->to($event->email);
				$message->subject(sprintf('[%s] 비밀번호를 초기화하세요.', config('app.name')));
			}
		);
	}

    public function onUserCreated(\App\Events\UserCreated $event)
	{
		$user = $event->user;
		Mail::send('emails.' . app()->getLocale() . '.auth.confirm', compact('user'), function($message) use($user) {
			$message->to($user->email);
			$message->subject(sprintf('[%s] 회원 가입을 확인해 주세요.', config('app.name')));
		});
	}

    public function subscribe(Dispatcher $events)
	{
		$events->listen(
			\App\Events\UserCreated::class,
			__CLASS__ . '@onUserCreated'
		);
		$events->listen(
			\App\Events\PasswordRemindCreated::class,
			__CLASS__ . '@onPasswordRemindCreated'
		);
	}
}
