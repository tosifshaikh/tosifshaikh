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
}
