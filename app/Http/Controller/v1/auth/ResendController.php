<?php
namespace App\Http\Controller\v1\auth;

use App\Core\JsonResponse;
use App\Exceptions\ValidationException;
use App\Http\Controller\Controller;
use App\Http\Request\v1\Auth\ResendRequest;
use App\jwt\JWTHandler;
use App\Model\v1\Customer;
use Carbon\Carbon;

class ResendController extends Controller
{
    public function update(ResendRequest $request)
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

                if (Carbon::now()->diffInSeconds(Carbon::createFromTimeString($res->updated_at)) < 120)
                {
                    throw new ValidationException("Verify code send in 2 minutes later" , 422);
                }

                return $res;

            })->then(function ($record) use ($request,$customer)
            {
                return $customer->generateVerifyCode()->then(function ($code) use ($record,$customer)
                {
                    if (!$code){
                        throw new \Exception("There is an error during verify code generate",500);
                    }

                    return $customer->update($record->id,
                        [
                            "verify_code"       => $code,
                        ])->then(function (){
                            return JsonResponse::created(["message" => "New verify code send successfully"]);
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