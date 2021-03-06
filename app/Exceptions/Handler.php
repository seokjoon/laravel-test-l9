<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		//if(app()->environment('production')) {
		/* if(app()->environment('local')) {
			if($exception instanceof ModelNotFoundException) {
				return response(view('errors.notice', [
					'title' => '찾을 수 없습니다.',
					'description' => '죄송합니다! 요청하신 페이지가 없습니다.',
				]), 404);
			}
		} */
		if(app()->environment('production')) {
			$sCode = 400;
			$title = '죄송합니다.';
			$desc = '에러가 발생했습니다.';

			if($exception instanceof ModelNotFoundException or $exception instanceof NotFoundHttpException) {
				$sCode = 404;
				$desc = $exception->getMessage() ?: '요청하신 페이지가 없습니다.';
			}

			return response(view('errors.notice', [
				'title' => $title,
				'description' => $desc,
			]), $sCode);
		}

		return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
	{
		//return parent::unauthenticated($request, $exception); // TODO: Change the autogenerated stub

		if($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.', 401]);
		}
		return redirect()->guest(route('sessions.create'));
	}
}
