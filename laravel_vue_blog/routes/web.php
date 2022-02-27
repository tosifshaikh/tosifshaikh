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
use Illuminate\Support\Facades\Route;

Route::post('/post/create_tag','TagController@index');

Route::get('/', function () {
    return view('welcome');
});

 Route::any('{slug}', function () {
    return view('welcome');
});


