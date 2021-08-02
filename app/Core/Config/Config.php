<?php

namespace App\Core\Config;

class Config
{
    private static $instance;

    public function __construct()
    {
        //TODO config read directory
        //$folders
    }

    public static function getInstance(): Config
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }
        return self::$instance;
    }
}