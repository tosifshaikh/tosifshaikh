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
    public function index(Request $request)
    {

        //first check you are logged in and admin user
        // || if you are already logged in, check if you are an admin user
        if ((!Auth::check() && $request->path() != 'login')) {
            return redirect('/login');
        }
        if ((!Auth::check() && $request->path() == 'login')) {
            return view('welcome');
        }

        $user = Auth::user();
        if ($user->role->isAdmin == 0) {
            return redirect('/login');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }


        return view('welcome');
    }
    public function getUser()
    {
        return $this->user->where('role_id','!=',4)->orderBy('id','desc')->get();
    }
    public function create(Request $request)
    {
        $this->validate( $request,[
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'pass' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);
         $pass = bcrypt($request->pass);

         return  $this->user->create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' =>  $pass,
            'role_id' => $request->role_id,
         ]);
    }
    public function edit(Request $request)
    {
        $this->validate( $request,[
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'pass' => 'min:6',
            'role_id' => 'required',
        ]);
      $data =  [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id,
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
            'email' => "required|email",
            'pass' => 'bail|required|min:6',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->pass])) {
            $user = Auth::user();
            if ( $user->userType == 2) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect Login Details'
                    ],401);
            }
            return response()->json([
            'msg' => 'You are logged in',
            'user' => $user
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect Login Details'
                ],401);
        }
    }
    public function logout(Request $request)
    {
       Auth::logout();
       return redirect('/login');
    }
}
