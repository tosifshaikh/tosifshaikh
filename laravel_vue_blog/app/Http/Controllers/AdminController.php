<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;

    }
    public function index()
    {
        return $this->user->where('userType','!=',2)->orderBy('id','desc')->get();
    }
    public function create(Request $request)
    {
        $this->validate( $request,[
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'pass' => 'bail|required|min:6',
            'userType' => 'required',
        ]);
         $pass = bcrypt($request->pass);

         return  $this->user->create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' =>  $pass,
            'userType' => $request->userType,
         ]);
    }
    public function edit(Request $request)
    {
        $this->validate( $request,[
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'pass' => 'min:6',
            'userType' => 'required',
        ]);
      $data =  [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType,
      ];
        if ($request->pass) {
            $data['pass'] = bcrypt($request->pass);
        }


         return  $this->user->where('id',$request->id)->update( $data);
    }
    public function destroy()
    {
        # code...
    }
    public function login(Request $request)
    {
        $this->validate( $request,[
            'email' => "bail|required|email",
            'pass' => 'bail|required|min:6',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->pass])) {
                return response()->json([
                'msg' => 'You are logged in'
                ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect Login Details'
                ],401);
        }
    }
}
