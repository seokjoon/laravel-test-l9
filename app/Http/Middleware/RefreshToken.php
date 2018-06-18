<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class RefreshToken extends \Tymon\JWTAuth\Middleware\BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //return $next($request);
		try {
			$newToken = $this->auth->setRequest($request)->parseToken()->refresh();
		} catch (TokenExpiredException $e) {
			return $this->respond( 'tymon.jwt.expired', 'refresh_ttl_finished', $e->getStatusCode(), [$e] );
		} catch(JWTException $e) {
			return $this->respond( 'tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e] );
		}
		return response()->json(['token' => $newToken,], 201, [], JSON_PRETTY_PRINT);
    }
}
