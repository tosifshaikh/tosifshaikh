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

        return response()->json($this->category
            ->orderBy('created_at', 'desc')
            ->paginate(Category::PAGE),config('constant.STATUS.SUCCESS_CODE'));
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
            $file->move(Category::CATEGORY_PATH,$filename);
            $this->category->image = $filename;
        }
        if ( !$this->category->save()) {
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json( $this->category, config('constant.STATUS.SUCCESS_CODE'));
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
            $file->move(Category::CATEGORY_PATH,$filename);
            $category->image = $filename;
            if (File::exists(Category::CATEGORY_PATH.$oldPath)) {
                File::delete(Category::CATEGORY_PATH.$oldPath);
            }
          //  $request->file('image')->store('assets/uploads/category/'.$filename);
           // Storage::delete('assets/uploads/category/'.$oldPath );

        }


        if (!$category->update()) {
            if (File::exists(Category::CATEGORY_PATH.$oldPath)) {
                File::delete(Category::CATEGORY_PATH.$oldPath);
            }
            return response()->json(['message' => __('message.Error Msg'),
                'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json($category, config('constant.STATUS.SUCCESS_CODE'));

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
            $path = Category::CATEGORY_PATH.$category->image;
            if (!File::exists($path)) {
                return response()->json(['message' => __('message.Error Msg'),
                    'status_code' => config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE')],
                    config('constant.STATUS.INTERNAL_SERVER_ERROR_CODE'));
            }
            File::delete($path);
        }
        return response()->json(['message' => __('message.category.Deleted'),
            'status_code' => config('constant.STATUS.SUCCESS_CODE')],
            config('constant.STATUS.SUCCESS_CODE'));

    }
}
