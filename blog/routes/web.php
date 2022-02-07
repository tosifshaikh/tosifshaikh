<?php
//phpinfo();
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

Route::get('/', [\App\Http\Controllers\Frontend\FrontendController::class,'index']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth','isAdmin']],function (){
    Route::get('/dashboard', [\App\Http\Controllers\Admin\FrontendController::class,'index']);
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class,'index']);
    Route::get('/add-category',[\App\Http\Controllers\Admin\CategoryController::class,'add']);
    Route::post('/insert-category',[\App\Http\Controllers\Admin\CategoryController::class,'insert']);
    Route::post('/update-category/{id}',[\App\Http\Controllers\Admin\CategoryController::class,'update']);
    Route::get('/edit-category/{id}',[\App\Http\Controllers\Admin\CategoryController::class,'edit']);
    Route::get('/delete-category/{id}',[\App\Http\Controllers\Admin\CategoryController::class,'destroy']);
    Route::get('/products',[\App\Http\Controllers\Admin\ProductController::class,'index']);
    Route::get('/add-product',[\App\Http\Controllers\Admin\ProductController::class,'add']);
    Route::post('/insert-product',[\App\Http\Controllers\Admin\ProductController::class,'insert']);
    Route::get('/edit-product/{id}',[\App\Http\Controllers\Admin\ProductController::class,'edit']);
    Route::post('/update-product/{id}',[\App\Http\Controllers\Admin\ProductController::class,'update']);
    Route::get('/delete-product/{id}',[\App\Http\Controllers\Admin\ProductController::class,'destroy']);
});
