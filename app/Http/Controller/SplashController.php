<?php
namespace App\Http\Controller;

use Psr\Http\Message\ServerRequestInterface;

class SplashController extends Controller
{
    public function show(ServerRequestInterface $request)
    {
        $customer = getCustomer();
        $data = [
            "user" => null,
            "time" => time(),
            "ver"  => [
                    "ess"       =>  "1.0",
                    "i_ess"     =>  "1.0",
                    "i_not_ess" =>  "1.0",
                    "not_ess"   =>  "1.0"
                ]
        ];

        if ($customer){
            $data["user"] = $customer->toArray();
        }
        return response(["data" => $data]);
    }
}