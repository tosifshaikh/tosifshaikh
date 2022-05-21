<?php

namespace App\Http\Controllers;

use App\menucategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MenuCategoryController extends Controller
{
    //
    protected $menucategories;
    public function __construct(menucategories $menucategories)
    {
        $this->menucategories = $menucategories;
    }
    public function show($request = null, $params = [])
    {
      return $this->menucategories->orderBy('id', 'desc')->get();
    }
    public function add($request = null, $params = [])
    {
        $this->validate($request,[
             'category_name' => 'required'
         ]);

         $data= $this->menucategories->create([
            'category_name' => $request->category_name,
            'is_active' => $request->is_active
        ]);
        return response()->json( $data, Response::HTTP_OK);
    }
    public function delete($request = null)
    {
        $this->validate($request,[
            'id' => 'required'
        ]);
        $result = $this->menucategories->find($request->id)->delete();
        return response()->json( $result, Response::HTTP_OK);
    }
    public function edit($request = null)
    {
        $this->validate($request,[
            'id' => 'required',
            'category_name' => 'required'
        ]);
        $menucategories =$this->menucategories->where('id',$request->id)->first();
        $result = $menucategories->update(['category_name' => $request->category_name,'is_active' =>$request->is_active ]);
        $theUpdatedData = $menucategories->refresh();
        return response()->json($theUpdatedData, Response::HTTP_OK);

    }
}
