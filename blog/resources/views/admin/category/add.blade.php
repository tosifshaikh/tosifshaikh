@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Add Category</h4>
        </div>
        <div class="card-body">
            <form action="{{url('insert-category')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <lable for="">Name</lable>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <lable for="">slug</lable>
                        <input type="text" name="slug" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <lable for="">Description</lable>
                        <textarea name="description"  rows="3" class="form-control">  </textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <lable for="">Status</lable>
                        <input type="checkbox" name="status">
                    </div>
                    <div class="col-md-6 mb-3">
                        <lable for="">Popular</lable>
                        <input type="checkbox" name="popular">
                    </div>
                    <div class="col-md-12 mb-3">
                        <lable for="">Meta Title</lable>
                        <input type="text" name="meta_title" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <lable for="">Meta Keyword</lable>
                        <textarea name="meta_keyword"   rows="3" class="form-control"></textarea>
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
