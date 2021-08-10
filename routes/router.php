<?php

use App\Http\Controller\SplashController;
use App\Http\Controller\v1\auth\LoginController;
use App\Http\Controller\v1\auth\RegisterController;
use App\Core\Route\Route;


Route::get('splash', [SplashController::class , 'show']);

Route::group('v1' , function (){

    Route::group('auth',function (){
        Route::post('register'  , [RegisterController::class    , 'store']);
        Route::post('login'     , [LoginController::class       , 'store']);
    });

},['auth:admin']);
