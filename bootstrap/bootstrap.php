<?php


$env = \Dotenv\Dotenv::createImmutable(__DIR__,"../.env");
$env->load();



$container  = \App\Core\Container\Container::getInstance();

$loop       = \React\EventLoop\Factory::create();

$mysql      = new \React\MySQL\Factory($loop);

$container->add("db" , $mysql);


$aliases = include_once "app/Http/Middleware/MiddlewareAlias.php";

\App\Core\Route\Route::alias($aliases);

\App\Core\Route\Route::init(
                                new \FastRoute\RouteCollector(new \FastRoute\RouteParser\Std() ,
                                new \FastRoute\DataGenerator\GroupCountBased())
                            );

require_once "routes/router.php";

return $loop;