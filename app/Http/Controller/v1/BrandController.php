<?php
namespace App\Http\Controller\v1;

use App\Core\JsonResponse;
use App\Http\Controller\Controller;
use App\Core\Request\Request;
use App\Model\v1\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        return Brand::index()->then(function ($data){
            $res = [];

            $res["data"] =(array_map(function ($row){
                return $row->JsonResource();
            },$data));

            return response($res);
        })->otherwise(function (\Exception $exception){
            return JsonResponse::internalServerError($exception->getMessage());
        });
    }
}