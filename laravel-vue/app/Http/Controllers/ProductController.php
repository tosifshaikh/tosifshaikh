<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public $product;
    public $category;
    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @return Product
     */
    public function getCategories()
    {
        return response()->json($this->category->all(),200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //to fetch category name
        // return response()->json($this->product->with('Category')->orderBy('created_at', 'desc')->paginate(2),200);
        return response()->json($this->product->orderBy('created_at', 'desc')->paginate(2),200);
        
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

            [  'category_id' => 'required',
                'name' => "required",
                'image' => 'required|image' //mimes:jpeg,png

            ]
        );

        $this->product = new Product();
        $this->product->product_name = $request->name;
        $this->product->category_id = $request->category_id;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/product/',$filename);
            $this->product->image = $filename;
        }
        if ( !$this->product->save()) {
            return response()->json(['message' => 'Some Error Occured!, Please Try Again',
                'status_code' => 500],500);
        }

        return response()->json( $this->product, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate(
            [ 'name' => "required",
                'categoryID' => 'required',//mimes:jpeg,png

            ]
        );
        $product->product_name = $request->name;
        $product->category_id = $request->categoryID;
       
        $oldPath = $product->image;
        
        if ($request->hasFile('image') && $product->image != $request->file('image')) {
           $request->validate(
                [ 'image' => "image",
                    //mimes:jpeg,png

                ]
            );
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/product/',$filename);
            $product->image = $filename;
            if (File::exists('assets/uploads/product/'.$oldPath)) {
                File::delete('assets/uploads/product/'.$oldPath);
            }
            //  $request->file('image')->store('assets/uploads/category/'.$filename);
            // Storage::delete('assets/uploads/category/'.$oldPath );

        }


        if (!$product->update()) {
            if (File::exists('assets/uploads/product/'.$oldPath)) {
               // File::delete('assets/uploads/product/'.$oldPath);
            }
            return response()->json(['message' => 'Some Error Occured!, Please Try Again',
                'status_code' => 500],500);
        }
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            $path = 'assets/uploads/product/'.$product->image;
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
