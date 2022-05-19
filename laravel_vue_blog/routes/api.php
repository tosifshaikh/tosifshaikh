<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\loginCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::get('/app/create_tag','TagController@index');

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
/* Route::prefix('app')->middleware(Admincheck::class)->group(function(){
Route::post('/login',[AdminController::class,'login']);
}); */
Route::group(['prefix' => 'app'],function() {
    Route::post('login',[AdminController::class,'login'])->middleware(loginCheck::class);
   // Route::post('/logout',[AdminController::class,'logout'])->middleware('auth:api');
    //Route::post('logout',[AdminController::class,'logout']);
    Route::group(['middleware' => ['auth:api',loginCheck::class]],function () {
        /* Tag Module */
        Route::post('/create_tag',[TagController::class,'addTag']);
        Route::get('/get_tag',[TagController::class,'getTag']);
        Route::post('/edit_tag',[TagController::class,'editTag']);
        Route::post('/delete_tag',[TagController::class,'deleteTag']);\
        /* End Tag Module */
        Route::post('logout',[AdminController::class,'logout']);
        Route::get('/get_role',[RoleController::class,'getData']);
        Route::post('/edit_role',[RoleController::class,'edit']);
    });



});
//Route::post('/login',[AdminController::class,'login']);
/* Route::group(['middleware' => 'auth:api'],function() {

}); */
/* Route::prefix('api')->middleware('auth:api')->group(function(){
    Route::group(['middleware' => 'scope:Admin'], function () {
    Route::post('/login',[AdminController::class,'login']);
   });
}); */
