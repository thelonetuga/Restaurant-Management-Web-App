<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class IsWaiterOrManager
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
        if ($request->user() && ($request->user()->type == 'waiter' || $request->user()->type == 'manager')) {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Unauthorized, Only waiters and managers are alowed!'
        ], 401);
    }
}
