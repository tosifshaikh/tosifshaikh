<?php

use App\Http\Controllers\AdminController;
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
Route::group(['prefix' => 'auth','middleware' => loginCheck::class],function() {
    Route::post('login',[AdminController::class,'login']);

});
//Route::post('/login',[AdminController::class,'login']);
/* Route::group(['middleware' => 'auth:api'],function() {

}); */
/* Route::prefix('api')->middleware('auth:api')->group(function(){
    Route::group(['middleware' => 'scope:Admin'], function () {
    Route::post('/login',[AdminController::class,'login']);
   });
}); */
