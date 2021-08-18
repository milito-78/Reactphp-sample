<?php
namespace App\Model\v1;

use App\Core\DataBase\Model;
use Firebase\JWT\JWT;
use React\MySQL\QueryResult;
use React\Promise\Deferred;


class Customer extends Model
{
    protected string $table = "customers";

    //TODO singleton
    static private ?Customer $customer = null ;

    public static function setUser($user)
    {
        self::$customer = $user;
    }

    public static function getUser()
    {
        return self::$customer;
    }

    public function checkEmailExists($email)
    {
        $build = $this->builder
            ->select()
            ->setTable($this->table)
            ->where()
            ->equals("email" , $email)
            ->end()
            ->limit(0,1);

        $query = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                    ->then(function (QueryResult $result){
                                                        if (!@$result->resultRows[0])
                                                            return null;

                                                        return $this->makeOneMap($result->resultRows[0]);
                                                    });

    }


    public function generateVerifyCode()
    {
        $deferred = new Deferred();
        $deferred->resolve(rand(10000 , 99999));

        return $deferred->promise()
            ->then(function ($data){
                return $this->findByColumn('verify_code' , $data)->then(function ($res)
                {
                    return $this->generateVerifyCode();
                })->otherwise(function (\Exception $exception) use ($data){

                    if ($exception->getCode() == 404) {
                        return $data;
                    }
                    throw new \Exception($exception->getMessage() , $exception->getCode());

                });
            });

    }

}