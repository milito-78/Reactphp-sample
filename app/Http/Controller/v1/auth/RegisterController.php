<?php
namespace App\Http\Controller\v1\auth;


use App\Core\JsonResponse;
use App\Exceptions\ValidationException;
use App\Http\Controller\Controller;
use App\Http\Request\v1\Auth\RegisterRequest;
use App\Model\v1\Customer;
use Carbon\Carbon;


class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $customer = new Customer();

        return $customer->checkEmailExists($request->email)
            ->then(function ($res)
            {
                if (!$res){
                    return $res;
                }

                if ((!$res || !$res->verify_date) && Carbon::now()->diffInSeconds(Carbon::createFromTimeString($res->updated_at)) > 120)
                {
                     return $res;
                }

                throw new ValidationException("There is an account with this email" , 422);

            })->then(function ($res) use ($request,$customer){
                return $customer->generateVerifyCode()
                    ->then(function ($code) use ($res,$request,$customer)
                    {
                        if (!$code){
                            throw new \Exception("There is an error during verify code generate",500);
                        }

                        // TODO send email
                        if (!$res) {
                            $customer = $customer->create([
                                "verify_code" => $code,
                                "name" => $request->name,
                                "password" => password_hash($request->password, PASSWORD_BCRYPT),
                                "email" => $request->email,
                                "status" => "active"
                            ]);
                        }
                        else
                        {
                            $customer = $customer->update($res->id,
                                [
                                    "verify_code"       => $code,
                                    "name"              => $request->name,
                                    "password"          => password_hash($request->password, PASSWORD_BCRYPT),
                                ]);
                        }
                        return $customer->then(function (){
                            return JsonResponse::created(["message" => "Register done. Verify your account with an verify code we sent to your email."]);
                        });

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