@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Add Product</h4>
        </div>
        <div class="card-body">
            <form action="{{url('insert-product')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <select class="form-select form-control" aria-label="Default select example" name="cat_id">
                            <option value="0">[Select Category]</option>
                            @foreach ($category as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-6">
                        <lable for="">Name</lable>
                        <input type="text" name="product_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <lable for="">slug</lable>
                        <input type="text" name="slug" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <lable for="">Small Description</lable>
                        <textarea name="small_description"  rows="3" class="form-control">  </textarea>
                    </div>
                    <div class="col-md-12">
                        <lable for="">Description</lable>
                        <textarea name="product_description"  rows="3" class="form-control">  </textarea>
                    </div>
                    <div class="col-md-6">
                        <lable for="">Origial Price</lable>
                        <input type="number" name="original_price" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <lable for="">Selling Price</lable>
                        <input type="number" name="selling_price" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <lable for="">Tax</lable>
                        <input type="number" name="tax" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <lable for="">Qauntity</lable>
                        <input type="number" name="qty" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <lable for="">Status</lable>
                        <input type="checkbox" name="status">
                    </div>
                    <div class="col-md-6 mb-3">
                        <lable for="">Trending</lable>
                        <input type="checkbox" name="trending">
                    </div>
                    <div class="col-md-12 mb-3">
                        <lable for="">Meta Title</lable>
                        <input type="text" name="meta_title" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <lable for="">Meta Keyword</lable>
                        <textarea name="meta_keywords"   rows="3" class="form-control"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <lable for="">Meta Description</lable>
                        <textarea name="meta_description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="col-md-12">
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <button type="submit"  class="btn btn-primary">Submit</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
