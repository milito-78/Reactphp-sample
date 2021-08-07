<?php
namespace App\Http\Middleware;

use App\Core\Route\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Closure;
use React\Http\Message\Response;

class CorsMiddleware extends Middleware
{
    public function handle(ServerRequestInterface $serverRequest, Closure $middleware)
    {

        if (preg_match('/options/i',$serverRequest->getMethod()))
        {
            return json_no_content();
        }

        $serverRequest = $serverRequest->withAddedHeader('Access-Control-Allow-Origin','*');
        $serverRequest = $serverRequest->withAddedHeader('Content-Type','application/json');
        $serverRequest = $serverRequest->withAddedHeader('Access-Control-Allow-Credentials','true');
        $serverRequest = $serverRequest->withAddedHeader('Access-Control-Allow-Methods','GET, PUT, POST, DELETE, OPTIONS');
        $serverRequest = $serverRequest->withAddedHeader('Access-Control-Allow-Headers','Origin, Content-Type, X-Auth-Token , Authorization');

        return $middleware($serverRequest);
    }
}