<?php

use App\Http\Controller\SplashController;
use App\Http\Controller\v1\auth\LoginController;
use App\Http\Controller\v1\auth\RegisterController;
use App\Core\Route\Route;
use App\Http\Controller\v1\auth\ResendController;
use App\Http\Controller\v1\auth\VerifyAccountController;
use App\Http\Controller\v1\BrandController;
use App\Http\Controller\v1\CategoryController;
use App\Http\Controller\v1\CategoryProductController;
use App\Http\Controller\v1\ExplorerController;
use App\jwt\JWTHandler;

Route::get('jwt_generate[/{id}]' , function (\Psr\Http\Message\RequestInterface $request ,$id = null){

    if (!$id){

    }

    $jwt = JWTHandler::encode(null);

    return response(["token" => $jwt]);
});



Route::group('v1' , function (){

    Route::get('splash', [SplashController::class , 'show'],['auth:splash']);


    Route::group('auth',function (){
        Route::post('register'  , [RegisterController::class            , 'store']);
        Route::post('login'     , [LoginController::class               , 'store']);
        Route::put('verify'     , [VerifyAccountController::class       , 'update']);
        Route::put('resend'     , [ResendController::class              , 'update']);
    },['auth:guest']);

    Route::group("/" , function (){
        Route::get('categories'                     , [CategoryController::class , "index"]);
        Route::get("brands"                         , [BrandController::class, "index"]);
        Route::get("explorer"                       , [ExplorerController::class, "index"]);
    });
});


