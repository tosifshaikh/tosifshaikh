<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    const TOKEN_EXPIRY = 1;

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'emails' => 'required|string|emails',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if (!$user->save()) {
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }
        return response()
            ->json([
                'message' => __('message.auth.Create'),
                'status_code' => config('constant.STATUS.SUCCESS_REQ_RES_CODE')],
                config('constant.STATUS.SUCCESS_REQ_RES_CODE'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember-me' => 'boolean'
        ]);
     
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()
                ->json(['message' => __('Invalid Credentials'),
                    'status_code' => config('constant.STATUS.UNAUTHORIZED_CODE')],
                    config('constant.STATUS.UNAUTHORIZED_CODE'));
        }
        $user = $request->user();
        if ($user->role == 'admin') {
            $tokenData = $user->createToken('Personal Access Token', ['admin']);
        } else {
            $tokenData = $user->createToken('Personal Access Token', ['user']);
        }
        $token = $tokenData->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(AuthController::TOKEN_EXPIRY);
        }
        if (!$token->save()) {
            return \response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }
        return response()->json([
            'user' => $user,
            'acccess_token' => $tokenData->accessToken,
            'token_type' => 'Bearer',
            'token_scope' => $tokenData->token->scopes[0],
            'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDayDateTimeString(),
            'status_code' => config('constant.STATUS.SUCCESS_CODE')
        ], config('constant.STATUS.SUCCESS_CODE'));
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return \response()->json(['message' => __('message.auth.Log Out'),
            'status_code' => config('constant.STATUS.SUCCESS_CODE')],
            config('constant.STATUS.SUCCESS_CODE'));
    }

    public function profile(Request $request)
    {
        if (!$request->user()) {
            return \response()->json(['message' => __('message.auth.Not Log In'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }
        return \response()->json($request->user(), config('constant.STATUS.SUCCESS_CODE'));
    }

    public function resetPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return \response()->json([
                'message' => __('message.auth.Invalid Email'),
                'status_code' => config('constant.STATUS.UNAUTHORIZED_CODE')],
                config('constant.STATUS.UNAUTHORIZED_CODE'));
        } else {
            $random = rand(111111, 999999);
            $user->verification_code = $random;
            if ($user->save()) {
                $userData = [
                    'email' => $user->email,
                    'full_name' => $user->name,
                    'random' => $random
                ];
                Mail::send('emails.reset', $userData, function ($message) use ($userData) {
                    $message->from('no-reply@laravel.vue.learning', 'Password Request');
                    //$message->sender('');
                    $message->to($userData['email'], $userData['full_name']);
                    ////   $message->cc('');
                    //  $message->bcc('');
                    //  $message->replyTo('');
                    $message->subject('Reset Password Request');
                    // $message->priority('');
                    // $message->attach('');
                });
                if (Mail::failures()) {
                    return \response()->json([
                        'message' => __('message.Fail'),
                        'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                        config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
                } else {
                    return \response()->json([
                        'message' => __('message.auth.Email Verification'),
                        'status_code' => config('constant.STATUS.SUCCESS_CODE')],
                        config('constant.STATUS.SUCCESS_CODE'));
                }

            } else {
                return \response()->json([
                    'message' => __('message.Fail'),
                    'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                    config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
            }
        }

    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verificationCode' => 'required|integer',
            'password' => 'required|confirmed'
        ]);
        $user = User::where([
            'email' => $request->email,
            'verification_code' => $request->verificationCode])
            ->first();
        if (!$user) {
            return \response()->json([
                'message' => __('message.auth.User Not Found'),
                'status_code' => config('constant.STATUS.UNAUTHORIZED_CODE')],
                config('constant.STATUS.UNAUTHORIZED_CODE'));
        } else {
            $user->password = bcrypt(trim($request->password));
            $user->verification_code = null;
            if ($user->save()) {
                return \response()->json([
                    'message' => __('message.auth.Password Changed'),
                    'status_code' => config('constant.STATUS.SUCCESS_CODE')], config('constant.STATUS.SUCCESS_CODE'));


            } else {
                return \response()->json([
                    'message' => __('message.Fail'),
                    'status_code' => config('constant.STATUS.UNAUTHORIZED_CODE')],
                    config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
            }
        }

    }
}
