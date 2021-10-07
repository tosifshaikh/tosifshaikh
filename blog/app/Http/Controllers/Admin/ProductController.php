<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    private $product;
    private $category;
    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    public function index()
    {
        $product = $this->product->where('is_delete','=',0)->get();
        return view('admin.product.index', ['product' => $product]);
    }
    public function add()
    {
          $category= $this->category->where('is_delete','=',0)->get(['id','name']);
        return view('admin.product.add', ['category' => $category]);
    }
    public function insert(Request $request)
    {
        $this->product = new Product();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/product/',$filename);
            $this->product->image = $filename;
        }

        $this->product->product_name = $request->input('product_name');
        $this->product->cat_id = $request->input('cat_id');
        $this->product->slug = $request->input('slug');
        $this->product->product_description = $request->input('product_description');
        $this->product->small_description = $request->input('small_description');
        $this->product->original_price = $request->input('original_price');
        $this->product->selling_price = $request->input('selling_price');
        $this->product->tax = $request->input('tax');
        $this->product->qty = $request->input('qty');
        $this->product->status = $request->input('status') == true ? 1 : 0;
        $this->product->trending = $request->input('trending') == true ? 1 : 0;
        $this->product->meta_title = $request->input('meta_title');
        $this->product->meta_description = $request->input('meta_description');
        $this->product->meta_keywords = $request->input('meta_keywords');
        $this->product->save();
        return redirect('/products')->with('status','Product Added Successfully');
    }
    public function edit($id)
    {
        return view('admin.product.edit',[
            'product' => $this->product->find($id)
        ]);
    }
    public function update(Request $request, $id)
    {
        $this->product = $this->product->find($id);
        if ($request->hasFile('image')) {
            $path = 'assets/uploads/product/'.$this->product->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time(). '.'.$ext;
            $file->move('assets/uploads/product/',$filename);
            $this->product = $filename;
        }
        $this->product->product_name = $request->input('product_name');
       // $this->product->cat_id = $request->input('cat_id');
        $this->product->slug = $request->input('slug');
        $this->product->product_description = $request->input('product_description');
        $this->product->small_description = $request->input('small_description');
        $this->product->original_price = $request->input('original_price');
        $this->product->selling_price = $request->input('selling_price');
        $this->product->tax = $request->input('tax');
        $this->product->qty = $request->input('qty');
        $this->product->status = $request->input('status') == true ? 1 : 0;
        $this->product->trending = $request->input('trending') == true ? 1 : 0;
        $this->product->meta_title = $request->input('meta_title');
        $this->product->meta_description = $request->input('meta_description');
        $this->product->meta_keywords = $request->input('meta_keywords');
        $this->product->update();
        return redirect('/products')->with('status','Product Updated Successfully');
    }
    public function destroy($id)
    {
        $this->product = $this->product->find($id);
        if ($this->product->image) {
            $path = 'assets/uploads/product/'.$this->product->image;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        $this->product->is_delete=1;
        $this->product->update();
        return redirect('/products')->with('status','Product Deleted Successfully');
    }
}
