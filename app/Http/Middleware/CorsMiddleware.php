<?php
namespace App\Http\Middleware;

use App\Core\Route\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Closure;

class CorsMiddleware extends Middleware
{
    public function handle(ServerRequestInterface $serverRequest, Closure $middleware)
    {
        return $middleware($serverRequest);
    }
}