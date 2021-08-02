<?php


namespace App\Http\Middleware;


use Psr\Http\Message\ServerRequestInterface;

class Guard
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
        var_dump("Gurad middle");
        return call_user_func_array($this->middleware, [$serverRequest,...$args]);
    }
}