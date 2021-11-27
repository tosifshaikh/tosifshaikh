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
                'status_code' => 500], 500);
        }
        return response()->json(['message' => __('message.auth.Create'), 'status_code' => 201], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'emails' => 'required|string|emails',
            'password' => 'required|string',
            'remember-me' => 'boolean'
        ]);
        if (!Auth::attempt(['emails' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid username/password', 'status_code' => 401], 401);
        }
        $user = $request->user();
        if ($user->role == 'admin') {
            $tokenData = $user->createToken('Personal Access Token', ['admin']);
        } else {
            $tokenData = $user->createToken('Personal Access Token', ['user']);
        }
        $token = $tokenData->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        if (!$token->save()) {
            return \response()->json(['message' => 'Some Error Occured, Please Refresh and Try again', 'status_code' => 500], 500);
        }
        return response()->json([
            'user' => $user,
            'acccess_token' => $tokenData->accessToken,
            'token_type' => 'Bearer',
            'token_scope' => $tokenData->token->scopes[0],
            'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDayDateTimeString(),
            'status_code' => 200
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return \response()->json(['message' => 'Logout Successfully!', 'status_code' => 200], 200);
    }

    public function profile(Request $request)
    {
        if (!$request->user()) {
            return \response()->json(['message' => 'Not Logged in', 'status_code' => 500], 500);
        }
        return \response()->json($request->user(), 200);
    }

    public function resetPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return \response()->json([
                'message' => 'We have sent a verification code to your emails address',
                'status_code' => 200], 200);
        } else {
            $random = rand(111111,999999);
            $user->verification_code = $random;
            if ($user->save()) {
                $userData = [
                    'email' => $user->email,
                    'full_name' => $user->name,
                    'random' => $random
                ];
                Mail::send('emails.reset',$userData,function ($message) use($userData){
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
                        'message' => 'Some error Occured',
                        'status_code' => 500], 500);
                } else {
                    return \response()->json([
                        'message' => 'We have sent a verification code to your email address',
                        'status_code' => 200], 200);
                }

            } else {
                return \response()->json([
                    'message' => 'Some error Occured',
                    'status_code' => 500], 500);
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
                'message' => 'User not found/Invalid code',
                'status_code' => 401], 401);
        } else {
            $user->password = bcrypt(trim($request->password));
            $user->verification_code = null;
            if ($user->save()) {
                    return \response()->json([
                        'message' => 'Password Changed Successfully',
                        'status_code' => 200], 200);


            } else {
                return \response()->json([
                    'message' => 'Some error Occured',
                    'status_code' => 500], 500);
            }
        }

    }
}
