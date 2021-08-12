<?php
namespace App\Http\Request\v1\Auth;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class RegisterRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            v::key('name' , v::allOf(v::notBlank(), v::stringType() , v::length(3, 80) ) )->setName("name"),
            v::key('email' , v::allOf(v::notBlank(), v::email() ) )->setName("email"),
            v::key('password' , v::allOf( v::notBlank(),v::stringType(),v::length(6, 32)  ) )->setName("password"),
        ];
    }
}