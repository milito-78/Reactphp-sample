<?php
namespace App\Http\Controller\v1\auth;

use App\Core\JsonResponse;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Http\Controller\Controller;
use App\Http\Request\v1\Auth\LoginRequest;
use App\jwt\JWTHandler;
use App\Model\v1\Customer;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $customer = new Customer();

        return
            $customer->findByColumn("email" , $request->email)
                ->then(function (Customer $record) use ($request,$customer)
                {
                    if (!password_verify($request->password,$record->password))
                    {
                        throw new ValidationException("Username or password is incorrect");
                    }

                    if (!$record->verify_date)
                    {
                        throw new ValidationException("Your account is not verified yet");
                    }

                    if ($record->status != "active")
                    {
                        throw new ForbiddenException("Your account is not active. Contact support.",403);
                    }
                    $jwt = JWTHandler::encode($record);

                    return response(
                        [
                            "data" =>[
                                        "id"        => (int)$record->id,
                                        "name"      => $record->name,
                                        "token"     => $jwt
                                    ],
                            "message" => "login successfully"
                    ]);

                })
                ->otherwise(function (ValidationException $exception)
                {
                    return JsonResponse::validationError($exception->getMessage());
                })
                ->otherwise(function (ForbiddenException $exception)
                {
                    return JsonResponse::Aborted($exception->getMessage());
                })
                ->otherwise(function (NotFoundException $exception)
                {
                    return JsonResponse::notFound("There is no account with this username. Please Register in before login");
                }
                )->otherwise(function (\Exception $exception)
                {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
                );
    }
}