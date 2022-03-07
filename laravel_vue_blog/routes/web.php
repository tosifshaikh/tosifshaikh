<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::post('/app/create_tag',[TagController::class,'addTag']);
Route::get('/app/get_tag',[TagController::class,'getTag']);
Route::post('/app/edit_tag',[TagController::class,'editTag']);
Route::post('/app/delete_tag',[TagController::class,'deleteTag']);
Route::post('/app/upload',[CategoryController::class,'upload']);
Route::post('/app/delete_image',[CategoryController::class,'deleteImage']);
Route::post('/app/create_category',[CategoryController::class,'addCategory']);
Route::get('/app/get_category',[CategoryController::class,'getCategory']);
Route::post('/app/delete_category',[CategoryController::class,'delete']);
Route::post('/app/edit_category',[CategoryController::class,'edit']);
Route::post('/app/create_user',[AdminController::class,'create']);
Route::get('/app/get_user',[AdminController::class,'index']);
Route::post('/app/delete_user',[AdminController::class,'destroy']);
Route::post('/app/edit_user',[AdminController::class,'edit']);



Route::get('/', function () {
    return view('welcome');
});

 Route::any('{slug}', function () {
    return view('welcome');
});


