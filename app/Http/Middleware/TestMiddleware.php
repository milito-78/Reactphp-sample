<?php

namespace App\Http\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class TestMiddleware
{
    /**
     * @var callable
     */
    private $middleware;

    public function __construct( callable $middleware)
    {
        $this->middleware = $middleware;
    }

    public function __invoke($serverRequest,...$args)
    {
        var_dump("TEts middle");
        return call_user_func_array($this->middleware, [$serverRequest,...$args]);
    }
}