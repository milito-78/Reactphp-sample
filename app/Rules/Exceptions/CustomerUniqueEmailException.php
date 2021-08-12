<?php


namespace App\Rules\Exceptions;

use Respect\Validation\Exceptions\ValidationException;


class CustomerUniqueEmailException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Error on validation',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'Email is exist',
        ],
    ];
}