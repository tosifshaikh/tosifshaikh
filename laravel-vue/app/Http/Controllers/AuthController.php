<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =bcrypt($request->password) ;

        if (!$user->save()) {
            return response()->json(['message' => 'Some Error Occured!, Please Try Again',
                'status_code' => 500],500);
        }
        return response()->json(['message' => 'User Created Successfully.', 'status_code' => 201],201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember-me' => 'boolean'
        ]);
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid username/password', 'status_code' => 401 ],401);
        }
        $user = $request->user();
        if ($user->role == 'Admin') {
            $tokenData = $user->createToken('Personal Access Token', ['do_anything']);
        } else {
            $tokenData = $user->createToken('Personal Access Token', ['can_create']);
        }
        $token = $tokenData->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        if (!$token->save()) {
            return \response()->json(['message' => 'Some Error Occured, Please Refresh and Try again','status_code' => 500],500);
        }
        return response()->json([
            'user' => $user,
            'acccess_token' => $tokenData->accessToken,
            'token_type' => 'Bearer',
            'token_scope' => $tokenData->token->scopes[0],
            'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDayDateTimeString(),
            'status_code' => 200
        ],200);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return \response()->json(['message' => 'Logout Successfully!','status_code' => 200],200);
    }
}
