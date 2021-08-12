<?php


namespace App\Core\Validation;


use App\Rules\ValidationRules;

class Factory
{
    public static function setCustomValidation()
    {
        $validations = ValidationRules::rules();

        foreach ($validations as $validation)
        {
            \Respect\Validation\Factory::setDefaultInstance(
                                (new \Respect\Validation\Factory())
                                    ->withRuleNamespace($validation["validation"])
                                    ->withExceptionNamespace($validation["exception"])
                            );
        }
    }
}