<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\loginCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


static $actionArr = [
    '1' => 'App\Http\Controllers\MenuCategoryController',
];
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
         Route::match(['get', 'post'], '{controller}/{action}/{params1?}/{params2?}', function ($controller, $action = 'index',Request $request) {
            static $pathArr = [
                'menu-category' => App\Http\Controllers\MenuCategoryController::class,
            ];
          //  $request = new \Illuminate\Http\Request();
            $app = app();
            $uri = $request->path();
            if(!empty($uri)) {
                    $params = explode('/', $uri);
                    \array_splice(  $params,0,3);
            }
            $controller = $app->make( $pathArr[$controller]);
           // dd($params);

            return $controller->callAction($action, [$request,$params] );
        });
     /*  Route::any('{controller}/{action?}/{params1?}/{params2?}',function($controller, $action = 'index', $params1 = '',$params2 = ''){
        dd($controller);

        });  */
        //Route::get('/menu-category',[MenuCategoryController::class,'getData']);

       // get-menu-category
        /* Tag Module */
        Route::post('/create_tag',[TagController::class,'addTag']);
        Route::get('/get_tag',[TagController::class,'getTag']);
        Route::post('/edit_tag',[TagController::class,'editTag']);
        Route::post('/delete_tag',[TagController::class,'deleteTag']);
        /* End Tag Module */
        /* Category Module */
        Route::post('/upload',[CategoryController::class,'upload']);
        Route::post('/delete_image',[CategoryController::class,'deleteImage']);
        Route::post('/create_category',[CategoryController::class,'addCategory']);
        Route::get('/get_category',[CategoryController::class,'getCategory']);
        Route::post('/delete_category',[CategoryController::class,'delete']);
        Route::post('/edit_category',[CategoryController::class,'edit']);
        /* End Category Module */

        Route::post('/create_user',[AdminController::class,'create']);
        Route::get('/get_user',[AdminController::class,'getUser']);
        Route::post('/delete_user',[AdminController::class,'destroy']);
        Route::post('/edit_user',[AdminController::class,'edit']);

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
