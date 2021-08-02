<?php
namespace App\Http\Controller;

use App\Exceptions\NotFoundException;
use Psr\Http\Message\RequestInterface;
use Exception;

class  HeaderController extends Controller
{
    public function index(RequestInterface $request,$a,$b )
    {
        return Option::header()->then(function ($result){
            if (is_array($result)) {
                $data = array_map(function ($res) {
                    return $res->toArray();
                }, $result);
            }
            else
            {
                $data = $result->toArray();
            }
            return response($data);
        })->otherwise(function (NotFoundException $e){
            return abort($e->getCode() , $e->getMessage());
        })->otherwise(function (Exception $e){
            return abort($e->getCode() , $e->getMessage());
        });
        return response([$a,$b,$c]);
    }
}