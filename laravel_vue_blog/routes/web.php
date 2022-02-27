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

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::post('/app/create_tag',[TagController::class,'addTag']);
Route::get('/app/get_tag',[TagController::class,'getTag']);
Route::post('/app/edit_tag',[TagController::class,'editTag']);
Route::post('/app/delete_tag',[TagController::class,'deleteTag']);



Route::get('/', function () {
    return view('welcome');
});

 Route::any('{slug}', function () {
    return view('welcome');
});


