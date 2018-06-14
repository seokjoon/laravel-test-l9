<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticationOnceWithBasicAuth
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
		return auth()->onceBasic() ?: $next($request);
    }
}
