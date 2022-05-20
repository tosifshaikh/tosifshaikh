<?php

namespace App\Http\Controllers;

use App\menucategories;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuCategoryController extends Controller
{
    //
    protected $menucategories;
    public function __construct(menucategories $menucategories)
    {
        $this->menucategories = $menucategories;
    }
    public function show($request)
    {
       dd($request);
    }
    public function save(Request $request)
    {
        $this->validate($request,[
             'categoryName' => 'required'
         ]);
         $data= $this->menucategories->create([
            'category_name' => $request->categoryName,
        ]);
        return response()->json( $data, Response::HTTP_OK);
    }
}
