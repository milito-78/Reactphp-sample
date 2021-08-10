<?php


namespace App\Http\Middleware;

use App\Core\Route\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware extends Middleware
{
    private string $guard;

    public function __construct(callable $middleware, string $guard = "guest")
    {
        parent::__construct($middleware);
        $this->guard = $guard;
    }

    public function handle(ServerRequestInterface $serverRequest, \Closure $middleware)
    {

        $token = $serverRequest->getHeader("Authorization");
        //TODO jwt checked

        return $middleware($serverRequest);
    }
}