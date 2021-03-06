<?php

namespace App\Core\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Respect\Validation\Validator;


abstract class FormRequest implements ValidationRequest, ServerRequestInterface
{
    use ServerRequestImplementsTrait;


    public ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request) {
        $this->request = $request;
    }

    public function __get($name)
    {
        if (property_exists($this , $name)){
            return $this->{$name};
        }

        $parsed_body = $this->request->getParsedBody();
        if (isset($parsed_body[$name]))
        {
            return $parsed_body[$name];
        }

        $query_params = $this->getQueryParams();
        if (isset($query_params[$name]))
        {
            return $query_params[$name];
        }

        return null;
    }

    public function validate(): void
    {
        Validator::allOf(...$this->rules())->assert($this->request->getParsedBody());
    }

    public function rules() : array
    {
        return [];
    }
 }
