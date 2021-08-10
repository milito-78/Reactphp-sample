<?php
namespace App\Http\Controller\v1\auth;

use App\Http\Controller\Controller;
use App\Http\Request\v1\Auth\RegisterRequest;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        return response($request->x);
    }

}