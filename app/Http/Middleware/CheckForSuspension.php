<?php

namespace App\Http\Middleware;

use Closure;

class CheckForSuspension
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
        if (auth()->check() && auth()->user()->is_suspended) {
            auth()->logout();

            return redirect()->route('login')->withError('Your account is suspended.');
        }

        return $next($request);
    }
}
