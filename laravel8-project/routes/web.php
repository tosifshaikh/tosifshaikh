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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user', [App\Http\Controllers\UserDetailController::class, 'index'])->name('user');
Route::get('/user/list', [App\Http\Controllers\UserDetailController::class, 'Show'])->name('user.list');
Route::post('/user/add', [App\Http\Controllers\UserDetailController::class, 'Save'])->name('user.add');

//Datatable
Route::get('/user-datatable', [App\Http\Controllers\UserDetailController::class, 'indexDatatable']);
 Route::post('/user-datatable/Edit', [App\Http\Controllers\UserDetailController::class, 'edit'])->name('user-edit');
Route::post('/user-datatable/Add', [App\Http\Controllers\UserDetailController::class, 'Save'])->name('user-add');
Route::get('/user-datatable/Delete', [App\Http\Controllers\UserDetailController::class, 'delete'])->name('user-delete');
Route::get('/user-datatable/list', [App\Http\Controllers\UserDetailController::class, 'Show'])->name('user-list');
