<?php
   /* header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
    header('Access-Control-Allow-Origin: *');*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['prefix' => 'auth'],function() {
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('login',[\App\Http\Controllers\AuthController::class,'login']);
    Route::group(['middleware' => 'auth:api'],function () {
        Route::get('logout',[\App\Http\Controllers\AuthController::class,'logout']);
    });
});
    Route::group(['prefix' => 'user'],function() {
        Route::group(['middleware' => 'auth:api'],function () {
            Route::post('edit-category', function () {
                return \response()->json(['message' => 'Admin Access', 'status_code' => 200],200);
            })->middleware('scope:do_anything');
            Route::post('create-category', function () {
                return \response()->json(['message' => 'Everyone Access', 'status_code' => 200],200);
            })->middleware('scopes:do_anything,can_create');
        });
    });

Route::resource('/categories', \App\Http\Controllers\CategoryController::class);
