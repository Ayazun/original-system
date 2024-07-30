<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/',function(){
    return view('welcome');
});

Auth::routes(); //

Route::get('/home', ['App\Http\Controllers\HomeController::class', 'index'])->name('home');
Route::get('/products/index', 'App\Http\Controllers\ProductController@index')->name('products.index');
Route::get('/products/create','App\Http\Controllers\ProductController@create')->name('products.create');
Route::get('/products/show/{id}','App\Http\Controllers\ProductController@show')->name('products.show');
Route::post('/products/store','App\Http\Controllers\ProductController@store')->name('products.store');
Route::delete('/products/{id}','App\Http\Controllers\ProductController@destroy')->name('products.destroy');
Route::get('/products/edit/{id}','App\Http\Controllers\ProductController@edit')->name('products.edit');
Route::post('/products/update/{id}','App\Http\Controllers\ProductController@update')->name('products.update');
Route::get('/products/regist','ProductController@showRegistForm')->name('regist');
Route::post('/products/regist','ProductController@registSubmit')->name('submit');
Route::get('products/search','App\Http\Controllers\ProductController@search')->name('products.search');



