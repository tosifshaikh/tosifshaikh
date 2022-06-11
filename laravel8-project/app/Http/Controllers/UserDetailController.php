<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UserDetailController extends Controller
{
    protected $userDetail;
    protected $hobby;
    public function __construct(UserDetail $userDetail,Hobby $hobby)
    {
        $this->userDetail = $userDetail;
        $this->hobby = $hobby;
    }
    public function index(Request $request)
    {
       // dd($request);
       return Response()->view('userdetail');
    }
    public function Save(Request $request)
    {

        $this->validate( $request,[
            'name' => 'required',
            'email' => 'bail|required|email|unique:App\Models\UserDetail,email',
            'gender' => 'int|required',
            'bdate' => 'required',
            'file' => 'required'
        ]);

        try {
            $this->userDetail->name= $request->name;
            $this->userDetail->email= $request->email;
            $this->userDetail->gender = $request->gender;
            $imageName = time().'.'.$request->file->extension();
            $request->file->move(public_path().'/uploads/userdetail/',$imageName);
            $this->userDetail->file = $imageName;
            $this->userDetail->bdate = $request->bdate;

            $data = $this->userDetail->insert();
            if($request->has('hobbies') && !empty($request->hobbies)) {
            $hobbies = [];
            $hobbies = array_map(function ($v) use ($data) {
                return ['userDetail_id' => $v, 'hobby_id' => $data->id];
            }, $request->hobbies);
            $this->hobby->insert($hobbies);

            }
            DB::commit();
            $this->getUserDetail($data->id);
            return redirect()->json($data, HttpFoundationResponse::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
        }


    }
    public function Show(Request $request)
    {

    }
    private function getUserDetail($id= null) {
        if(!empty($id)) {
                dd($this->userDetail->find($id)->get()) ;
        }
        return $this->userDetail->all();
    }

}
