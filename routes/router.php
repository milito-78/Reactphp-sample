<?php

use Psr\Http\Message\RequestInterface;
use App\Core\Route\Route;


Route::group('x',function ()  {

    Route::group('/test',function () {

        Route::GET('/',function (RequestInterface $request) {
            return response(["slash"]);
        });
        Route::GET('/index2',function (RequestInterface $request) {
            return response(["index2"]);
        });

        Route::GET('/index1/{x}/{y}',function (RequestInterface $request,$x,$y) {
            return response(["index21"]);
        });

        Route::GET('/index12',function (RequestInterface $request) {
            return response(["index21"]);
        });

        Route::POST('/index12/{test}',function (RequestInterface $request) {
            return response(["post method"]);
        });

    });

});


