<?php

use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth','isAdmin']],function (){
    Route::get('/dashboard', [\App\Http\Controllers\Admin\FrontendController::class,'index']);
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class,'index']);
    Route::get('/category-add',[\App\Http\Controllers\Admin\CategoryController::class,'add']);
});
