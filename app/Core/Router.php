<?php
namespace App\Core;

use App\Core\Container\Container;
use App\Core\Request\FormRequest;
use App\Core\Route\ValidationTrait;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\NotFoundException;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\ServerRequest;


final class Router{

    use ValidationTrait;

    private GroupCountBased $dispatch;

    public function __construct(RouteCollector $collector)
    {
        $this->dispatch = new GroupCountBased($collector->getData());
    }
    
    public function __invoke(ServerRequestInterface $request)
    {
        $route = $this->dispatch->dispatch(
            $request->getMethod(), $request->getUri()->getPath()
        );

        switch ($route[0]){
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new NotFoundException("Route Not Found",404);
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $method = $route[1][0];
                $req_method =  $request->getMethod();
                throw new MethodNotAllowedException("Method '$req_method' does not supported. Supported method is '$method' ",405);
            case \FastRoute\Dispatcher::FOUND:
                $params = array_values($route[2]);

                $request = $this->checkRequestInstance($route[1], $request);

                $container = Container::getInstance();

                try
                {
                    $container->add("request" ,$request);
                }
                catch (\Exception $exception)
                {
                }

                return $route[1]($request, ...$params);
        }

        throw new \LogicException("wrong");
    }


    private function checkRequestInstance($route,$request)
    {

        $validation = $this->getController($route);

        if ($validation)
        {
            $request = new $validation($request);
        }

        return $request;
    }

}