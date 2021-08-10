<?php
namespace App\Http\Request\v1\Auth;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class RegisterRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            v::key('x' , v::allOf( v::positive() ) )->setName("price")
        ];
    }
}