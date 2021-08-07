<?php

use App\Core\JsonRequestDecoder;
use App\Core\Router;
use App\Exceptions\ErrorHandler;

use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use React\Http\Server;

require "vendor/autoload.php";



$loop = include_once "bootstrap/bootstrap.php";

$option =  function (\Psr\Http\Message\RequestInterface $request , callable $next){
    if (preg_match('/options/i',$request->getMethod()))
    {
        return json_no_content();
    }
    return $next($request);
};

$server = new Server($loop, $option, new ErrorHandler(), new JsonRequestDecoder(), new Router(\App\Core\Route\Route::getCollector()));


$socket = new \React\Socket\Server("127.0.0.1:3000", $loop);

$server->listen($socket);


$logger = new Logger('errors');
$logger->pushHandler(new StreamHandler(__DIR__.'/storage/logs/app.logs', Logger::DEBUG));
$logger->pushHandler(new FirePHPHandler());


$server->on("error" , function ($data)use($logger) {
    $logger->error(get_class($data) . ' ' . $data->getMessage());
});



echo "server run on 127.0.0.1:3000\n";

$loop->run();
