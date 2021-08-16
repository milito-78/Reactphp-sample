<?php


namespace App\Http\Middleware;

use App\Core\JsonResponse;
use App\Core\Route\Middleware;
use App\Exceptions\AuthorizationException;
use App\jwt\JWTHandler;
use App\Model\v1\Customer;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware extends Middleware
{
    private string $guard;

    public function __construct(callable $middleware, string $guard = "guest")
    {
        parent::__construct($middleware);
        $this->guard = $guard;
    }

    public function handle(ServerRequestInterface $serverRequest, \Closure $middleware)
    {
        $token = $serverRequest->getHeader("Authorization");

        if (!isset($token[0])){
            throw new AuthorizationException("Token is required" , 401);
        }

        try
        {
            $decode = (new JWTHandler())->decode($token[0]);
        }
        catch (\UnexpectedValueException $exception)
        {
            throw new AuthorizationException($exception->getMessage() , 401);
        }

        if ($this->guard == "guest")
        {
            if ($decode->id)
            {
                throw new AuthorizationException("Token is invalid" , 401);
            }
        }
        else
        {
            if (!$decode->id)
            {
                throw new AuthorizationException("You need to login. Token is invalid" , 401);
            }
            else
            {
                return (new Customer())
                    ->findByColumn("id" , $decode->id)
                    ->then(function ($data) use($middleware,$serverRequest)
                    {
                        if ($data->status != 'active' || !$data->verify_date)
                        {
                            return JsonResponse::Aborted("Your account is not active. Contact Supports");
                        }

                        return $middleware($serverRequest);

                    })->otherwise(function (\Exception $exception)
                    {
                        return JsonResponse::unAuthorized("Token is invalid");
                    });
            }
        }
    }
}