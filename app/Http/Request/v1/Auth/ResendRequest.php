<?php
namespace App\Http\Request\v1\Auth;

use App\Core\Request\FormRequest;
use Respect\Validation\Validator as v;

class ResendRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            v::key('email',
                v::allOf(v::notBlank(),
                    v::email()
                ))->setName("email"),
        ];
    }
}