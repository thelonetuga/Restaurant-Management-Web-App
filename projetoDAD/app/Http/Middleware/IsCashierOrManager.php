<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class IsCashierOrManager
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
        if ($request->user() && ($request->user()->type == 'cashier' || $request->user()->type == 'manager')){
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Unauthorized, Only cashiers and managers are allowed!'
        ], 401);
    }
}
