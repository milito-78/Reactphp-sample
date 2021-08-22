<?php


namespace App\Core\Request;


trait InputTrait
{
    public function has($input): bool
    {
        if ($this->{$input}){
            return true;
        }

        return false;
    }

    public function input($input)
    {
        if ($this->{$input}){
            return $this->{$input};
        }

        return null;
    }
}