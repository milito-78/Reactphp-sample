<?php

namespace App\Core\Route;

use App\Core\Container\Container;
use FastRoute\RouteCollector;

class Route
{
    use InitRouteCollectorTrait , MiddlewareTrait;

    static private string $uri = "";


    public function GET($path , callable $function, array $middleware = [])
    {
        $path = self::$uri . static::uriSlashCheck($path);
        $prev_middleware    = static::$middleware;
        $prev_middlewares   = static::$middlewares;

        if (!static::$middleware)
        {
            static::$middleware = $function;
        }

        $middleware = array_merge( self::$middlewares, $middleware);

        foreach ($middleware as $middle)
        {
            static::$middleware = new $middle(static::$middleware);
        }

        self::$router->get($path,static::$middleware);

        static::$middleware = $prev_middleware;
        static::$middlewares = $prev_middlewares;
    }


    public function POST($path , callable $function, array $middleware = [])
    {
        $path = self::$uri . static::uriSlashCheck($path);
        $prev_middleware    = static::$middleware;
        $prev_middlewares   = static::$middlewares;

        if (!static::$middleware)
        {
            static::$middleware = $function;
        }

        $middleware = array_merge( self::$middlewares, $middleware);

        foreach ($middleware as $middle)
        {
            static::$middleware = new $middle(static::$middleware);
        }

        self::$router->post($path,static::$middleware);

        static::$middleware = $prev_middleware;
        static::$middlewares = $prev_middlewares;
    }

    public function PUT($path , callable $function, array $middleware = [])
    {
        $path = self::$uri . static::uriSlashCheck($path);
        $prev_middleware    = static::$middleware;
        $prev_middlewares   = static::$middlewares;

        if (!static::$middleware)
        {
            static::$middleware = $function;
        }

        $middleware = array_merge( self::$middlewares, $middleware);

        foreach ($middleware as $middle)
        {
            static::$middleware = new $middle(static::$middleware);
        }

        self::$router->put($path,static::$middleware);

        static::$middleware = $prev_middleware;
        static::$middlewares = $prev_middlewares;
    }


    public function PATCH($path , callable $function, array $middleware = [])
    {
        $path = self::$uri . static::uriSlashCheck($path);
        $prev_middleware    = static::$middleware;
        $prev_middlewares   = static::$middlewares;

        if (!static::$middleware)
        {
            static::$middleware = $function;
        }

        $middleware = array_merge( self::$middlewares, $middleware);

        foreach ($middleware as $middle)
        {
            static::$middleware = new $middle(static::$middleware);
        }

        self::$router->patch($path,static::$middleware);

        static::$middleware = $prev_middleware;
        static::$middlewares = $prev_middlewares;
    }


    public function DELETE($path , callable $function, array $middleware = [])
    {
        $path = self::$uri . static::uriSlashCheck($path);
        $prev_middleware    = static::$middleware;
        $prev_middlewares   = static::$middlewares;

        if (!static::$middleware)
        {
            static::$middleware = $function;
        }

        $middleware = array_merge( self::$middlewares, $middleware);

        foreach ($middleware as $middle)
        {
            static::$middleware = new $middle(static::$middleware);
        }

        self::$router->delete($path,static::$middleware);

        static::$middleware = $prev_middleware;
        static::$middlewares = $prev_middlewares;
    }


    public function OPTION($path , callable $function, array $middleware = [])
    {
        $path = self::$uri . static::uriSlashCheck($path);
        $prev_middleware    = static::$middleware;
        $prev_middlewares   = static::$middlewares;

        if (!static::$middleware)
        {
            static::$middleware = $function;
        }

        $middleware = array_merge( self::$middlewares, $middleware);

        foreach ($middleware as $middle)
        {
            static::$middleware = new $middle(static::$middleware);
        }

        self::$router->addRoute("options", $path,static::$middleware);

        static::$middleware = $prev_middleware;
        static::$middlewares = $prev_middlewares;
    }


    public function GROUP($prefix , callable $function, array $middleware = [])
    {
        $previousGroupPrefix = self::$uri;

        self::$uri = $previousGroupPrefix . static::uriSlashCheck($prefix);


        $prev_middleware = static::$middlewares;

        self::$middlewares = array_merge(self::$middlewares , $middleware);

        $function();

        static::$middleware = $prev_middleware;
        self::$uri = $previousGroupPrefix;
    }


    public static function __callStatic($method , $arguments)
    {
        $method = strtoupper($method);

        return (new static)->$method(...$arguments);
    }

    public static function uriSlashCheck($path)
    {
        if (strlen($path) == 0 || $path == '/') {
            if (self::$uri == '')
                return '/';
            return '';
        }

        if (substr($path , 0,1) != '/')
            $path =  '/' . $path;
        if (substr($path,-1) == '/')
            $path = substr($path,0,-1);

        return $path;
    }

}