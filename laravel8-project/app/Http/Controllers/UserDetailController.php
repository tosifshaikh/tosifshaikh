<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\UserDetail;
use File;
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
       //return Response()->view('userdetail',['collection'=>$this->getUserDetail(),'Hobbies' =>  Hobby::HOBBIES]);
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
                        return ['userDetail_id' =>$this->userDetail->id, 'hobby_id' => $v, 'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                    ];
                    }, $request->hobbies);

                //Insert doesn't insert timestamps
                $this->hobby->insert($hobbies);

            }

          // $data=  $this->userDetail->find($this->userDetail->id)->get();
           // DB::commit();
           // dd( $data) ;
            return response()->json($this->getUserDetail($this->userDetail->id), HttpFoundationResponse::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);

        }


    }
    public function Show(Request $request)
    {
        ## Read value
     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); // Rows display per page

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; // Column index
     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
     $searchValue = $search_arr['value']; // Search value

     // Total records
     $totalRecords = $this->userDetail->select('count(*) as allcount')->count();
     $totalRecordswithFilter = $this->userDetail->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

     // Fetch records
     $records = $this->userDetail->with(['hobby'])->orderBy($columnName,$columnSortOrder)
       ->where('user_details.name', 'like', '%' .$searchValue . '%')
       ->select('user_details.*')
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;
        $name = $record->name;
        $email = $record->email;
        $gender = $record->gender;
        $file = $record->file;
        $bdate = $record->bdate;
        $hobby = $record->hobby;
        $data_arr[] = array(
          "id" => $id,
          'DT_RowId' => $id,
          "name" => $name,
          "email" => $email,
          'gender' =>  $gender,
          "genderFormated" => ( $gender == 1) ? 'Male' : 'Female',
          "file" => $file,
          "bdate" => $bdate,
          "hobby" => $hobby,
          'formatedHobbies' => $this->formatHobbies($hobby),
          "action" => '<button type="button" class="btn btn-warning edit" >Edit</button>
                <button type="button" class="btn btn-danger delete">Delete</button>'
        );
     }

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     return json_encode($response);
     //exit;

        //return response()->json($this->getUserDetail(), HttpFoundationResponse::HTTP_OK);
    }
    public function formatHobbies($hobbies = [])
    {
        $hobbyarr = [];
        foreach($hobbies as $k => $value) {
            $hobbyarr[]=$value->hobby_id;
        }
        return implode(',',$hobbyarr);
    }
    public function getUserDetail($id= null) {

        if(!empty($id)) {
            return $this->userDetail->with(['hobby'])->where('id','=',$id)->first();
        }
        return $this->userDetail->with(['hobby'])->orderBy('id','desc')->get();
    }


    public function indexDatatable(Request $request)
    {
        $data = $this->getUserDetail();
        return Response()->view('userdetailDatatable',['collection' => $data]);
    }
    public function edit(Request $request)
    {
        $this->validate( $request,[
            'id' => 'int|required',
            'name' => 'required',
            'email' => "bail|required|email|unique:App\Models\UserDetail,email,$request->id",
            'gender' => 'int|required',
            'bdate' => 'required',
            'file' => 'required',

        ]);
        $id = $request->id;
        $userData = $this->userDetail->find($id);
          $userData->name= $request->name;
         $userData->email= $request->email;
         $userData->gender = $request->gender;
         if(File::exists(public_path().'/uploads/userdetail/', $userData->file)) {
            File::delete(public_path().'/uploads/userdetail/', $userData->file);
         }
        $imageName = time().'.'.$request->file->extension();
        $request->file->move(public_path().'/uploads/userdetail/',$imageName);
        $userData->file = $imageName;
        $userData->bdate = $request->bdate;
        //Mutator from model will not applied if any other method will be used except save()
        $userData->update();

        $this->hobby->where('userDetail_id','=', $request->id)->delete();

        if($request->has('hobbies') && !empty($request->hobbies)) {

            $hobbies =[];
            $hobbies = array_map(function ($v) use($id) {
                    //print_r($v);
                    return ['userDetail_id' =>$id, 'hobby_id' => $v,'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
                ];
                }, $request->hobbies);

            //Insert doesn't insert timestamps
            $this->hobby->insert($hobbies);

        }
        return response()->json($this->getUserDetail($id), HttpFoundationResponse::HTTP_OK);
    }
    public function delete(Request $request)
    {

        $this->validate( $request,[
            'id' => 'int|required']);
        $id = $request->id;
        $this->userDetail->where('id','=',$id)->delete();
        $this->hobby->where('userDetail_id','=',$id)->delete();
        return response()->json($id, HttpFoundationResponse::HTTP_OK);
    }

}
