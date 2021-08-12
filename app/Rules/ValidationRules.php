<?php


namespace App\Rules;


class ValidationRules
{
    public static function rules()
    {
        return [
            [
                "validation" => "App\\Rules\\Rules",
                "exception" => "App\\Rules\\Exceptions"
            ]
        ];
    }
}