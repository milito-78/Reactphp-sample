<?php

namespace App\Core\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Respect\Validation\Validator;


abstract class FormRequest extends Request implements ValidationRequest
{

    public function validate(): void
    {
        Validator::allOf(...$this->rules())->assert($this->request->getParsedBody());
    }

    public function rules() : array
    {
        return [];
    }
 }
