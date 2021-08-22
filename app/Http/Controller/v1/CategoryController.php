<?php
namespace App\Http\Controller\v1;

use App\Core\JsonResponse;
use App\Core\Request\Request;
use App\Http\Controller\Controller;
use App\Model\v1\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return Category::index($request)->then(function ($data) {
            $res = [];

            $res["data"] =(array_map(function ($row){
                return $row->JsonResource();
            },$data["data"]));

            $res["meta"] = $data["meta"];

            return response($res);

        })->otherwise(function (\Exception $exception){
            return JsonResponse::internalServerError($exception->getMessage());
        });

    }

}