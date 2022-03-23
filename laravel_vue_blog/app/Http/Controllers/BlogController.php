<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Blogcategory;
use App\Blogtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    protected $blog;
    protected $blogCategory;
    protected $blogTag;
    public function __construct(Blog $blog, Blogcategory $blogCategory, Blogtag $blogTag)
    {
        $this->blog = $blog;
        $this->blogCategory = $blogCategory;
        $this->blogTag = $blogTag;
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
        $categories = $request->category_id;
        $tags = $request->tag_id;
        DB::beginTransaction();
        try {
            $blogID= $this->blog->create([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'meta_description' => $request->meta_description,
                'jsonData' => $request->jsondata
           ]);

            if (!empty($categories)) {
                $blogCategories = array_map(function ($v) use ($blogID) {
                    return ['category_id' => $v, 'blog_id' => $blogID->id];
                }, $categories);
                $this->blogCategory->insert($blogCategories);
            }

            if (!empty($tags)) {
                $blogTags = array_map(function ($v) use ($blogID) {
                    return ['tag_id' => $v, 'blog_id' => $blogID->id];
                }, $tags);
                $this->blogTag->insert($blogTags);
            }
            DB::commit();
            return 'donje';
        } catch (\Throwable $th) {
            DB::rollBack();
            return 'Not done';
        }

    }
    public function blogData(Request $request)
    {
        $blog = $this->blog->with(['tag','cat']);
        if(!empty($request->id)) {
           return  $blog->where('id','=',$request->id)->first();
        }else {
           return  $blog->orderBy('id','desc')->get();
        }
    }
    public function deleteBlog(Request $request)
    {
       return $this->blog->where('id',$request->id)->delete();
    }
}
