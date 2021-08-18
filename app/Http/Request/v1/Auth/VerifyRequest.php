<?php
namespace App\Http\Request\v1\Auth;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class VerifyRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            v::key('email',
                v::allOf(v::notBlank(),
                    v::email()
                ))->setName("email"),

            v::key('verify_code' ,
                v::allOf( v::notBlank(),v::numericVal(),
                    v::min(10000),
                    v::max(99999)
                ))
                ->setName("password"),
        ];
    }
}