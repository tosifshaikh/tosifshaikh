<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $category = $this->category->where('is_delete','=',0)->get();
        return view('admin.category.index', ['category' => $category ]);
    }
    public function add()
    {
        return view('admin.category.add');
    }
    public function insert(Request $request)
    {
        $category = new Category();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/category/',$filename);
            $category->image = $filename;
        }
        $category->name = $request->input('name');
        $category->slug = $request->input('slug');
        $category->description = $request->input('description');
        $category->status = $request->input('status') == true ? 1 : 0;
        $category->popular = $request->input('popular') == true ? 1 : 0;
        $category->meta_title = $request->input('meta_title');
        $category->meta_description = $request->input('meta_description');
        $category->meta_keywords = $request->input('meta_keyword');
        $category->save();
        return redirect('/categories')->with('status','Category Added Successfully');
    }
    public function edit($id)
    {
        $category = $this->category->find($id);
        return view('admin.category.edit',['category' => $category ]);
    }
    public function update(Request $request, $id)
    {
        $category = $this->category->find($id);
        if ($request->hasFile('image')) {
           $path = 'assets/uploads/category/'.$category->image;
           if (File::exists($path)) {
               File::delete($path);
           }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/category/',$filename);
            $category->image = $filename;
        }
        $category->name = $request->input('name');
        $category->slug = $request->input('slug');
        $category->description = $request->input('description');
        $category->status = $request->input('status') == true ? 1 : 0;
        $category->popular = $request->input('popular') == true ? 1 : 0;
        $category->meta_title = $request->input('meta_title');
        $category->meta_description = $request->input('meta_description');
        $category->meta_keywords = $request->input('meta_keyword');
        $category->update();
        return redirect('/dashboard')->with('status','Category Updated Successfully');
    }
    public function destroy($id)
    {
        $category = $this->category->find($id);
        if ($category->image) {
            $path = 'assets/uploads/category/'.$category->image;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $category->is_delete=1;
        $category->update();
        return redirect('/categories')->with('status','Category Deleted Successfully');
    }

}
