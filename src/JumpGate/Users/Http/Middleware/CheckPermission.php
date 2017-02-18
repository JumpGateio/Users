<?php

namespace JumpGate\Users\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param null                      $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        if (! app('Illuminate\Contracts\Auth\Guard')->guest()) {
            if ($request->user()->can($permission)) {
                return $next($request);
            }

            return $request->ajax ? response('Forbidden.', 403) : redirect(route('login'));
        }
    }
}
