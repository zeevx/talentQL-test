<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


//Authentication
Route::group(['prefix' => 'auth'], function(){
    Route::post('login', ['App\Http\Controllers\Api\Auth\AuthController','login']);
    Route::post('register', ['App\Http\Controllers\Api\Auth\AuthController','register']);
    Route::post('unauthorised', ['App\Http\Controllers\Api\Auth\AuthController','unauthorised']);
});
Route::group(['prefix'=> 'auth','middleware' => 'authenticate'], function () {
    Route::get('logout', ['App\Http\Controllers\Api\Auth\AuthController','logout']);
});

//User Information
Route::group(['prefix'=> 'user','middleware' => 'authenticate'], function () {
    Route::get('/', ['App\Http\Controllers\Api\UserController','index']);
    Route::post('/update', ['App\Http\Controllers\Api\UserController','update']);
});

//Products
Route::group(['prefix'=> 'product','middleware' => ['authenticate','product-owner']], function () {
    Route::get('/all', ['App\Http\Controllers\Api\ProductController','index']);
    Route::post('/add', ['App\Http\Controllers\Api\ProductController','store']);
    Route::get('/show/{id}', ['App\Http\Controllers\Api\ProductController','show']);
    Route::post('/update/{id}', ['App\Http\Controllers\Api\ProductController','update']);
    Route::get('/delete/{id}', ['App\Http\Controllers\Api\ProductController','destroy']);
});

//Photograph
Route::group(['prefix'=> 'photograph','middleware' => ['authenticate','photographer']], function () {
    Route::get('/all', ['App\Http\Controllers\Api\PhotographController','index']);
    Route::post('/add', ['App\Http\Controllers\Api\PhotographController','store']);
    Route::get('/show/{id}', ['App\Http\Controllers\Api\PhotographController','show']);
    Route::post('/update/{id}', ['App\Http\Controllers\Api\PhotographController','update']);
    Route::get('/delete/{id}', ['App\Http\Controllers\Api\PhotographController','destroy']);
});


