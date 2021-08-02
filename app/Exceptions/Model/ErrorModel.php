<?php

namespace App\Exceptions\Model;

final class ErrorModel{

    public $title;
    /**
     * @var array
     */
    public $message;

    public function __construct($title , $message = [])
    {
        $this->title = $title;
        $this->message = $message;
    }


    public static function error($title , $message = [])
    {
        $data = [
            "title" => $title,
            "errors" => $message
        ];

        return $data;
    }

    public function toArray()
    {
        return [
            "title"     => $this->title,
            "errors"    => $this->message
        ];
    }
}