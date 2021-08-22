<?php
namespace App\Http\Controller\v1;

use App\Core\Request\Request;
use App\Http\Controller\Controller;

class ExplorerController extends Controller
{
    public function index(Request $request)
    {
        //TODO
        if ($request->has("category") && $request->input("category"))
        {

        }
        else
        {

        }
    }
}