<?php

namespace App\Http\Middleware;

use Closure;

class IsManager
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
        if($request->user() && $request->user()->type != "manager") {
            abort(403);
        }
        if ($request->user() && $request->user()->type == "manager") {
            return $next($request);
        }
        // return $next($request);
        return redirect()->route('login');
    }
}
