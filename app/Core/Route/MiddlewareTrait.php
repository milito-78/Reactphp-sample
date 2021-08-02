<?php

namespace App\Core\Route;

trait MiddlewareTrait
{
    static private array $middlewares = [];

    /**
     * @var callable
     */
    static private  $middleware = null;

}