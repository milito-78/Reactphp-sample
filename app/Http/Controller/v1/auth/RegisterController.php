<?php
namespace App\Http\Controller\v1\auth;


use App\Core\JsonResponse;
use App\Exceptions\ValidationException;
use App\Http\Controller\Controller;
use App\Http\Request\v1\Auth\RegisterRequest;
use App\Model\v1\Customer;


class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $customer = new Customer();

        return $customer->checkEmailExists($request->email)
            ->then(function ($res)
            {

                if (!$res)
                {
                     return true;
                }

                throw new ValidationException("There is an account with this email" , 422);

            })->then(function ($res) use ($request,$customer){
                if ($res)
                {

                    return $customer->generateVerifyCode()
                        ->then(function ($code) use ($request)
                        {
                            if (!$code){
                                throw new \Exception("There is an error during verify code generate",500);
                            }

                            // TODO send email
                            $customer = new Customer();
                            return $customer->create([
                                "verify_code"       => $code,
                                "name"              => $request->name,
                                "password"          => password_hash($request->password, PASSWORD_BCRYPT),
                                "email"             => $request->email,
                                "status"            => "active"
                            ])->then(function (){
                                return JsonResponse::created("Register done. Verify your account with an verify code we sent to your email.");
                            });
                        });
                }
            })

            ->otherwise(function (ValidationException $exception
            ){
                return JsonResponse::validationError($exception->getMessage());
            })->otherwise(function (\Exception $exception){
                return JsonResponse::internalServerError($exception->getMessage());
            });

    }

}