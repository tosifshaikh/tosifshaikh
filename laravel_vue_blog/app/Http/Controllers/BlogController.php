<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $blog;
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }
    public function uploadEditorImage(Request $request)
    {
        $this->validate($request,[
            // 'categoryName' => 'required',
             'image' => 'required|mimes:jpg,jpeg,png'
         ]);
         $imageName = time().'.'.$request->image->extension();
          $request->image->move(public_path().'/uploads/blog/',$imageName);
          return response()->json([
            'success'=> 1,
            'file' => [ 'url' =>
                url('/uploads/blog/'.$imageName)]
          ]);
          return $imageName;
    }
    public function slug(Request $request)
    {
       $title = 'Title slug';
       return $title;
    }
}
