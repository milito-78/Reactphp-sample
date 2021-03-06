<?php


namespace App\Core\Request;


interface ValidationRequest
{
    public function validate(): void;

    public function rules() : array;

}