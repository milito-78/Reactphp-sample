<?php
namespace App\Http\Controller\v1\auth;

use App\Core\JsonResponse;
use App\Exceptions\ValidationException;
use App\Http\Controller\Controller;
use App\Http\Request\v1\Auth\VerifyRequest;
use App\jwt\JWTHandler;
use App\Model\v1\Customer;
use Carbon\Carbon;

class VerifyAccountController extends Controller
{
    public function update(VerifyRequest $request)
    {
        $customer = new Customer();

        return $customer->checkEmailExists($request->email)
            ->then(function ($res) use ($request)
            {
                if (!$res){
                    throw new ValidationException("There is no account with this email" , 422);
                }

                if ($res->verify_date)
                {
                    throw new ValidationException("Your account has already been verified. Login with email and password" , 422);
                }

                if (Carbon::now()->diffInSeconds(Carbon::createFromTimeString($res->updated_at)) > 120 || $res->verify_code !=  $request->verify_code)
                {
                    throw new ValidationException("Your verify code is invalid." , 422);
                }

                return $res;

            })->then(function ($record) use ($request,$customer)
            {
                return $customer->update($record->id,[
                        "verify_date" => time()
                    ])
                    ->then(function () use ($record)
                    {
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
                    });
            })
            ->otherwise(function (ValidationException $exception)
            {
                return JsonResponse::validationError($exception->getMessage());
            })
            ->otherwise(function (\Exception $exception)
            {
                return JsonResponse::internalServerError($exception->getMessage());
            });
    }
}