<?php
namespace App\Model\v1;

use App\Core\DataBase\Model;

class Customer extends Model
{
    protected string $table = "customers";

    //TODO singleton
    private static self $customer ;


}