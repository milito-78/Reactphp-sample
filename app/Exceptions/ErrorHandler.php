<?php

namespace App\Exceptions;

use App\Core\JsonResponse;
use React\Http\Message\ServerRequest;

use Throwable;

final class ErrorHandler
{
    public function __invoke(ServerRequest $request, callable $next)
    {
        global $server;

        try {
            return $next($request);
        }
        catch (ValidationException $exception){
            $server->emit("error" , [$exception]);
            return JsonResponse::validationError($exception->getMessage());
        }
        catch (MethodNotAllowedException $exception){
            $server->emit("error" , [$exception]);

            return JsonResponse::methodNotAllowed($exception->getMessage());
        }
        catch (NotFoundException $exception){
            $server->emit("error" , [$exception]);

            return JsonResponse::notFound($exception->getMessage());
        }
        catch (AuthorizationException $exception){
            $server->emit("error" , [$exception]);

            return JsonResponse::unAuthorized($exception->getMessage());
        }
        catch (Throwable $error){
            $server->emit("error" , [$error]);

            return JsonResponse::internalServerError($error->getMessage());
        }

    }
}