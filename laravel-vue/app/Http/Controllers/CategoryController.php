<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        return response()->json($this->category->orderBy('created_at', 'desc')->paginate(2),200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [ 'name' => "required",
                'image' => 'required|image' //mimes:jpeg,png

            ]
        );
        $this->category = new Category();
        $this->category->name = $request->name;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/category/',$filename);
            $this->category->image = $filename;
        }
        if ( !$this->category->save()) {
            return response()->json(['message' => 'Some Error Occured!, Please Try Again',
                'status_code' => 500],500);
        }

        return response()->json( $this->category, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(
            [ 'name' => "required",
                 //mimes:jpeg,png

            ]
        );

        $category->name = $request->name;
        $oldPath = $category->image;
        if ($request->hasFile('image')) {
            $request->validate(
                [ 'image' => "image",
                    //mimes:jpeg,png

                ]
            );
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/category/',$filename);
            $category->image = $filename;
            if (File::exists('assets/uploads/category/'.$oldPath)) {
                File::delete('assets/uploads/category/'.$oldPath);
            }
          //  $request->file('image')->store('assets/uploads/category/'.$filename);
           // Storage::delete('assets/uploads/category/'.$oldPath );

        }


        if (!$category->update()) {
            if (File::exists('assets/uploads/category/'.$oldPath)) {
                File::delete('assets/uploads/category/'.$oldPath);
            }
            return response()->json(['message' => 'Some Error Occured!, Please Try Again',
                'status_code' => 500],500);
        }

        return response()->json($category, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->delete()) {
            $path = 'assets/uploads/category/'.$category->image;
            if (!File::exists($path)) {
                return response()->json(['message' => 'Some Error Occured!, Please Try Again',
                    'status_code' => 500],500);
            }
            File::delete($path);
        }
        return response()->json(['message' => 'Category deleted successfully!',
            'status_code' => 200],200);

    }
}
