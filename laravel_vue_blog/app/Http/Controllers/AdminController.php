<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;


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
            'email' => 'bail|required|email',
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
    public function edit()
    {
        # code...
    }
    public function destroy()
    {
        # code...
    }
}
