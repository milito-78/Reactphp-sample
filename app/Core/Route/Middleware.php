<?php


namespace App\Core\Route;


use Psr\Http\Message\ServerRequestInterface;

abstract class Middleware
{
    /**
     * @var callable
     */
    protected $middleware;

    public function __construct( callable $middleware)
    {
        $this->middleware = $middleware;


    }

    public function __invoke($serverRequest,...$args)
    {
        return $this->handle($serverRequest,$this->callback(...$args));
    }

    public function callback(...$args): \Closure
    {
        $middleware = $this->middleware;
        return function ($serverRequest) use ($middleware,$args)
        {
            return $middleware($serverRequest,...$args);
        };
    }

    abstract public function handle(ServerRequestInterface $serverRequest, \Closure $middleware);

}