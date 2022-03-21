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
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\Admincheck;
use Illuminate\Support\Facades\Route;

Route::prefix('app')->middleware(Admincheck::class)->group(function(){
    Route::post('/create_tag',[TagController::class,'addTag']);
    Route::get('/get_tag',[TagController::class,'getTag']);
    Route::post('/edit_tag',[TagController::class,'editTag']);
    Route::post('/delete_tag',[TagController::class,'deleteTag']);
    Route::post('/upload',[CategoryController::class,'upload']);
    Route::post('/delete_image',[CategoryController::class,'deleteImage']);
    Route::post('/create_category',[CategoryController::class,'addCategory']);
    Route::get('/get_category',[CategoryController::class,'getCategory']);
    Route::post('/delete_category',[CategoryController::class,'delete']);
    Route::post('/edit_category',[CategoryController::class,'edit']);
    Route::post('/create_user',[AdminController::class,'create']);
    Route::get('/get_user',[AdminController::class,'getUser']);
    Route::post('/delete_user',[AdminController::class,'destroy']);
    Route::post('/edit_user',[AdminController::class,'edit']);
    Route::post('/login',[AdminController::class,'login']);

    //Roles Route
    Route::post('/create_role',[RoleController::class,'create']);
    Route::get('/get_role',[RoleController::class,'getData']);
    Route::post('/edit_role',[RoleController::class,'edit']);
    Route::post('/assign-roles',[RoleController::class,'assignRole']);

    Route::post('/create-blog',[BlogController::class,'createBlog']);
    Route::get('/blog-data',[BlogController::class,'blogData']);
    Route::post('/delete_blog',[BlogController::class,'deleteBlog']);

});
Route::post('/create-blog',[BlogController::class,'uploadEditorImage']);
//Route::get('/blog-data',[BlogController::class,'blogData']);
Route::get('/slug',[BlogController::class,'slug']);

  Route::get('/',[AdminController::class,'index']);
    Route::get('/logout',[AdminController::class,'logout']);
    Route::any('{slug}',[AdminController::class,'index']);

/* Route::get('/', function () {
    return view('welcome');
});

 Route::any('{slug}', function () {
    return view('welcome');
});
 */

