<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class IsWaiterOrCook
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
        if ($request->user() && ($request->user()->type == 'cook' || $request->user()->type == 'waiter')) {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Unauthorized, Only cookers and waiters are alowed!'
        ], 401);

    }
}
