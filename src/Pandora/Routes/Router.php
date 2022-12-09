<?php

namespace Pandora\Routes;

use Closure;
use Pandora\Constants\ErrorResponse;
use Pandora\Constants\HttpMethod;
use Pandora\Exception\NotFoundException;
use Pandora\Server\Request;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    /**
     * @throws NotFoundException
     */
    final public function resolve(Request $request): Route
    {
        foreach ($this->routes[$request->getMethod()->value] as $route) {
            if ($route->matches($request->getUri())) {
                return $route;
            }
        }
        throw new NotFoundException("El recurso solicitado no existe!", ErrorResponse::NOT_FOUND->value);
    }

    final public function get(string $uri, Closure $action): void
    {
        $this->registerRouteAction(HttpMethod::GET, $uri, $action);
    }

    private function registerRouteAction(HttpMethod $method, string $uri, Closure $action): void
    {
        $this->routes[$method->value][$uri] = new Route($uri, $action);
    }

    final public function post(string $uri, Closure $action): void
    {
        $this->registerRouteAction(HttpMethod::POST, $uri, $action);
    }

    final public function put(string $uri, Closure $action): void
    {
        $this->registerRouteAction(HttpMethod::PUT, $uri, $action);
    }

    final public function patch(string $uri, Closure $action): void
    {
        $this->registerRouteAction(HttpMethod::PATCH, $uri, $action);
    }

    final public function delete(string $uri, Closure $action): void
    {
        $this->registerRouteAction(HttpMethod::DELETE, $uri, $action);
    }
}