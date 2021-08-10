<?php


namespace App\Core\Controller;


use Respect\Validation\Validator;

trait ValidationTrait
{
    public function validate(array $rules)
    {
        Validator::allOf(...$rules);
    }
}