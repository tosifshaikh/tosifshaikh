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
     //   DB::beginTransaction();
        try {
           /*  $userDetail =new UserDetail();
             $userDetail->name= $request->name;
             $userDetail->email= $request->email;
             $userDetail->gender = $request->gender;
            $imageName = time().'.'.$request->file->extension();
            $request->file->move(public_path().'/uploads/userdetail/',$imageName);
            $userDetail->file = $imageName;
            $userDetail->bdate = $request->bdate;
             $userDetail->save(); */
            // $userDetail =new UserDetail();
            $this->userDetail->name= $request->name;
            $this->userDetail->email= $request->email;
            $this->userDetail->gender = $request->gender;
            $imageName = time().'.'.$request->file->extension();
            $request->file->move(public_path().'/uploads/userdetail/',$imageName);
            $this->userDetail->file = $imageName;
            $this->userDetail->bdate = $request->bdate;
            //Mutator from model will not applied if any other method will be used except save()
            $this->userDetail->save();
            if($request->has('hobbies') && !empty($request->hobbies)) {
                $hobbies =[];
                $hobbies = array_map(function ($v) {
                        //print_r($v);
                        return ['userDetail_id' => $v, 'hobby_id' => $this->userDetail->id, 'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                    ];
                    }, $request->hobbies);

                //Insert doesn't insert timestamps
                $this->hobby->insert($hobbies);

            }
          $data=  $this->userDetail->with(['hobby'])->where('id','=',$this->userDetail->id)->first();
          // $data=  $this->userDetail->find($this->userDetail->id)->get();
           // DB::commit();
           // dd( $data) ;
            return response()->json($data, HttpFoundationResponse::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);

        }


    }
    public function Show(Request $request)
    {

    }
    private function getUserDetail($id= null) {
        dd($this->userDetail->find($id)->get()) ;
        if(!empty($id)) {

        }
        return $this->userDetail->all();
    }

}
