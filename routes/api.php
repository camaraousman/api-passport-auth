<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'auth'], function (){
   Route::post('login', [MainController::class, 'login']);
   Route::post('register', [MainController::class, 'register']);

   Route::group(['middleware'=>'auth:api'], function (){
      Route::get('logout', [MainController::class, 'logout']);
      Route::get('profile', [MainController::class, 'profile']);
   });
});

Route::group(['prefix'=>'users'], function (){
    Route::get('/', [UserController::class, 'index']);
});
