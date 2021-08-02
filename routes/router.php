<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\TestMiddleware;
use Psr\Http\Message\RequestInterface;
use App\Core\Route\Route;

class_alias(\App\Http\Middleware\Guard::class , 'GuardMiddleware');

Route::group('x',function ()  {

    Route::group('/test',function () {

        Route::GET('/',function (RequestInterface $request) {
            return response(["slash"]);
        });
        Route::GET('/index2',function (RequestInterface $request) {
            return response(["index2"]);
        });

        Route::GET('/index1/{x}/{y}',[\App\Http\Controller\HeaderController::class , "index"]);

        Route::GET('/index12',function (RequestInterface $request) {
            return response(["index21"]);
        });

        Route::POST('/index12/{test}',function (RequestInterface $request) {
            return response(["post method"]);
        });

    });

},[AuthMiddleware::class ]);


