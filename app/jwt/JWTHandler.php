<?php

namespace App\jwt;


use Firebase\JWT\JWT;
use Exception;
/** */
class JWTHandler
{
    //One hour
    const EXPIRED_AT = 60 * 60;

    protected string $jwt_secret;
    /**
     * @var mixed|null
     */

    public function __construct()
    {
        $this->jwt_secret = $_ENV["JWT_KEY"];
    }


    public static function verify($user = null ,$jwt_token = "")
    {
        $jwt = new self();
        $decode = $jwt->decode($jwt_token);

        if (!$user && $decode->id || $decode->id != $user->id)
        {
            throw new Exception("Token is invalid" , 401);
        }

        return $decode;
    }

    public static function encode($user = null)
    {
        $jwt = new self();
        $pay_load = [
            "id"        => (int)@$user->id,
            "name"      => @$user->name,
            "exp"       => time() + self::EXPIRED_AT
        ];

        return JWT::encode($pay_load,$jwt->jwt_secret);
    }

    public function decode($jwt_token)
    {
        return JWT::decode($jwt_token,$this->jwt_secret,["HS256"]);
    }

}