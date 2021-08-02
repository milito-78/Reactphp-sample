<?php
namespace App\Core\DataBase;


use App\Core\Container\Container;
use React\MySQL\Io\LazyConnection;

class DBConnection
{
    /**
     * @var LazyConnection
     */
    private static $instance;

    /**
     * @throws \App\Core\Container\NotFoundException
     * @throws \App\Core\Container\ContainerException
     */
    public function __construct()
    {
        $container = Container::getInstance();
        self::$instance = $container->get("db")->createLazyConnection($this->getUri());
    }

    /**
     * @return LazyConnection
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            new self();
        }
        return self::$instance;
    }

    private function getUri()
    {
        return  $_ENV["DB_USER"].":".$_ENV["DB_PASSWORD"]. "@".$_ENV["DB_HOST"] ."/".$_ENV["DB_NAME"];
    }

}