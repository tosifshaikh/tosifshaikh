<?php

use App\Http\Controllers\DatatableController;
use App\Models\Datatable;
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

Route::middleware(['splade'])->group(function () {
    // Route::get('/', fn () => view('home'))->name('home');
     Route::get('/', fn () => view('dashboard'))->name('dashboard');
     Route::get('/datatable', [DatatableController::class,'index'])->name('datatable');
   //  Route::get('/datatable', fn () => view('datatable'))->name('datatable');
    Route::get('/docs', fn () => view('docs'))->name('docs');

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();
});
