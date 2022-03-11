<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admincheck
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
        if ($request->path() == 'app/login') {
            return $next($request);
        }

        if (!Auth::check()) {
             return response()->json([
                'msg' => 'You are not allowed to access this route directly'
            ],403);
        }
        $user = Auth::user();
        if ($user->role->isAdmin == 0) {
            return response()->json([
                'msg' => 'You are not allowed to access this route'
            ],403);
        }
        return $next($request);
    }
}
