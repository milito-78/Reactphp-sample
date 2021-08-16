<?php

namespace App\Core\Container;

use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
    public function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
