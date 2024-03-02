<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Artisan;
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
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'super-admin',  'middleware' => 'super_admin'], function(){

    Route::get('/index',[SuperAdminController::class,'index'])->name('super_admin.index');
    Route::post('/store-vehical-record',[SuperAdminController::class,'store_vehical_record'])->name('super_admin.store_vehical_record');
    Route::post('/delete-vehical-record',[SuperAdminController::class,'delete_vehical_record'])->name('super_admin.delete_vehical_record');
    Route::post('/pay-vehical-record',[SuperAdminController::class,'pay_vehical_record'])->name('super_admin.pay_vehical_record');

});

Route::group(['prefix' => 'admin',  'middleware' => 'admin'], function(){

    Route::get('/index',[AdminController::class,'index'])->name('admin.index');

});
