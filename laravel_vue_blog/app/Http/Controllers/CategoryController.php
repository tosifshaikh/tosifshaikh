<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;
    public function __construct(Category $category) {
        $this->category = $category;
    }
    public function upload(Request $request)
    {
        $this->validate($request,[
           // 'categoryName' => 'required',
            'file' => 'required|mimes:jpg,jpeg,png'
        ]);
        $imageName = time().'.'.$request->file->extension();
         $request->file->move(public_path().'/uploads/category/',$imageName);
         return $imageName;
    }
    public function deleteImage(Request $request)
    {
       $fileName = $request->imageName;
       $this->deleteFileFromServer($fileName);
       return 'done';
    }
    public function deleteFileFromServer($fileName)
    {
       $filePath  = public_path().$fileName;
       if (file_exists($filePath)) {
           @unlink( $filePath );
       }
       return;
    }
    public function addCategory(Request $request)
    {
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
       return $this->category->create([
           'category_name' => $request->categoryName,
           'iconImage' => '/uploads/category/'.$request->iconImage
       ]);
    }
    public function getCategory(Request $request)
    {
        return$this->category->orderBy('id','desc')->get();
    }
}
