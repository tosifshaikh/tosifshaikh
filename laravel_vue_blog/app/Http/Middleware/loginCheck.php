<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class loginCheck
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

        /* if ($request->path() == 'app/login') {
            return $next($request);
        }*/

        if (!Auth::check() && !Auth::attempt(['email' => $request->email,
        'password' => $request->pass, 'isActive' => 1])) {
        return response()->json([
            'msg' => 'Invalid Credentials'
        ],Response::HTTP_UNAUTHORIZED);

        }

        $user = Auth::user();
        if($user->role->isAdmin == 0) {
            Auth::logout();
            return response()->json([
                'msg' => 'Incorrect Login Details'
                ],Response::HTTP_UNAUTHORIZED);

        }

        if (!Auth::check()) {
            return response()->json([

                'msg' => 'You are not allowed to access this route directly'
            ],Response::HTTP_FORBIDDEN);
        }

      /*   $user = Auth::user();
        if ($user->role->isAdmin == 0) {
            return response()->json([
                'msg' => 'You are not allowed to access this route'
            ],403);
        } */
        return $next($request);
    }
}
