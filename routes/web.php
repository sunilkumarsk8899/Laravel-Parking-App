<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'super-admin',  'middleware' => 'super_admin'], function(){

    Route::get('/index',[SuperAdminController::class,'index'])->name('super_admin.index');

});

Route::group(['prefix' => 'admin',  'middleware' => 'admin'], function(){

    Route::get('/index',[AdminController::class,'index'])->name('admin.index');

});