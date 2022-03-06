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
      // $filePath  = public_path().$fileName;
      $filePath = $fileName;
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
           'iconImage' => $request->iconImage
       ]);
    }
    public function getCategory(Request $request)
    {
        return $this->category->orderBy('id','desc')->get();
    }
    public function delete(Request $request)
    {
        $this->validate($request,[
            'id' => 'required'
        ]);
        $this->deleteFileFromServer( $request->iconImage);
       return $this->category->where('id','=',$request->id)->delete();
    }
    public function edit(Request $request)
    {

        $this->validate($request,[
            'category_name' => 'required',
            'iconImage' => 'required',
            'id' => 'required'
        ]);

        $oldData = $this->category->find($request->id);

        $this->deleteFileFromServer( $oldData->iconImage);

       return $this->category->where('id',$request->id)->update(['iconImage' => $request->iconImage,'category_name' => $request->category_name]);


    }
}
