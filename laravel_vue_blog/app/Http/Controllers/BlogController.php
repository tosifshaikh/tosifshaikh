<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
       return $this->blog->create([
            'title' => $title,
            'post' => 'some post',
            'post_excerpt' => 'ahead',
            'user_id' => 1,
            'meta_description' => 'ahead',
       ]);
    }
    public function createBlog(Request $request)
    {
        return $this->blog->create([
            'title' => $request->title,
            'post' => $request->post,
            'post_excerpt' => $request->post_excerpt,
            'user_id' => Auth::user()->id,
            'meta_description' => $request->meta_description,
            'jsonData' => $request->jsondata
       ]);
    }
}
