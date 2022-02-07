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
    Route::post('reset-password',[\App\Http\Controllers\AuthController::class,'resetPassword']);
    Route::post('reset-password-request',[\App\Http\Controllers\AuthController::class,'resetPasswordRequest']);
    Route::group(['middleware' => 'auth:api'],function () {
        Route::get('logout',[\App\Http\Controllers\AuthController::class,'logout']);
        Route::get('profile',[\App\Http\Controllers\AuthController::class,'profile']);
    });
});
    Route::group(['middleware' => 'auth:api'],function() {
            Route::group(['middleware' => 'scope:user,admin'], function () {
               /* Route::get('/user-scope', function() {
                    return  response()->json(['message' => 'User can access this'],200);
                });*/
                Route::get('/get_categories', [\App\Http\Controllers\ProductController::class,'getCategories']);
                Route::resource('/products',\App\Http\Controllers\ProductController::class);

            });
            Route::group(['middleware' => 'scope:admin'], function () {
                Route::resource('/categories', \App\Http\Controllers\CategoryController::class);
                Route::resource('/products',\App\Http\Controllers\ProductController::class);
                Route::resource('/ToDoList',\App\Http\Controllers\ToDoListController::class);
              //  Route::resource('/ToDoList-ADD',\App\Http\Controllers\ToDoListController::class);
                //Route::resource('/ToDoList',\App\Http\Controllers\ToDoListController::class);


                /*Route::get('/admin-scope', function() {
                    return  response()->json(['message' => 'Admin can access this'],200);
                });*/
            });
           /* Route::post('edit-category', function () {
                return \response()->json(['message' => 'Admin Access', 'status_code' => 200],200);
            })->middleware('scope:admin');
            Route::post('create-category', function () {
                return \response()->json(['message' => 'Everyone Access', 'status_code' => 200],200);
            })->middleware('scopes:admin,user');*/

    });

