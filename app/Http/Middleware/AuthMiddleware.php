<?php


namespace App\Http\Middleware;

use App\Core\Route\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware extends Middleware
{

    public function handle(ServerRequestInterface $serverRequest, \Closure $middleware)
    {
        return $middleware($serverRequest);
    }
}