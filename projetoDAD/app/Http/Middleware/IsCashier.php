<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsCashier
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
        if ($request->user() && $request->user()->type == 'cashier') {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Unauthorized, Only cashiers are allowed!'
        ], 401);
    }
}
