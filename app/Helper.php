<?php


if (!function_exists("response"))
{
    function response($data = null , $status = 200 , $headers = null): \App\Core\JsonResponse
    {
        return new \App\Core\JsonResponse($status , $data , $headers);
    }
}

if (!function_exists("json_no_content"))
{
    function json_no_content(): \App\Core\JsonResponse
    {
        return new \App\Core\JsonResponse(204 ,null);
    }
}

if (!function_exists("collect"))
{
    function collect($data = []): \Doctrine\Common\Collections\ArrayCollection
    {
        return new Doctrine\Common\Collections\ArrayCollection($data);
    }
}

/*
if (!function_exists("config"))
{
    function config()
    {
        $args = func_get_args();
        if (!count($args))
        {

        }
    }
}*/



if (!function_exists("abort")){
    function abort($status = 500 , $message = "Server Error")
    {
        $error = new \App\Exceptions\Model\ErrorModel("Server Error",$message);
        switch ($status)
        {
            case 404 :
                $error->title =  "Not Found";
                break;
            case 401 :
                $error->title =  "Unauthorized";
                break;
            case 405 :
                $error->title =  "Method Not Allowed";
                break;
            case 403 :
                $error->title =  "Access Denied";
                break;
            case 422 :
                $error->title =  "Validation Failed";
                break;
            case 500 :
            default :
                $error->title =  "Server Error";
                break;
        }

        return new \App\Core\JsonResponse( $status , $error->toArray());
    }
}

