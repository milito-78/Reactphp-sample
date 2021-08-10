<?php
namespace App\Http\Controller;

class SplashController extends Controller
{
    public function show()
    {
        return response("splash");
    }
}