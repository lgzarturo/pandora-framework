<?php

namespace Pandora\Routes;

use Closure;
use Pandora\Constants\ErrorMessage;
use Pandora\Constants\ErrorResponse;
use Pandora\Constants\HttpMethod;
use Pandora\Exception\NotFoundException;
use Pandora\Kernel\Middleware;
use Pandora\Server\Request;
use Pandora\Server\Response;

class Router {
    private array $routes = [];

    public function __construct() {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    /**
     * @throws NotFoundException
     */
    final public function resolveRoute(Request $request): Route {
        foreach ($this->routes[$request->getMethod()->value] as $route) {
            if ($route->matches($request->getUri())) {
                return $route;
            }
        }
        throw new NotFoundException(
            ErrorMessage::RESOURCE_NOT_FOUND->value,
            ErrorResponse::NOT_FOUND->value
        );
    }

    /**
     * @throws NotFoundException
     */
    final public function resolveResponse(Request $request): Response {
        $route = $this->resolveRoute($request);
        $request->setRoute($route);
        $action = $route->getAction();
        if ($route->hasMiddlewares()) {
            return $this->runMiddlewares(
                $route->getMiddlewares(),
                $request,
                $action
            );
        }
        return $action($request);
    }

    /* @var Middleware[] $middlewares */
    private function runMiddlewares(array $middlewares, Request $request, Closure $target): Response {
        if (count($middlewares) === 0) {
            return $target($request);
        }
        $next = fn ($request) => $this->runMiddlewares(
            array_slice($middlewares, 1),
            $request,
            $target
        );
        return $middlewares[0]->handle($request, $next);
    }

    private function registerRouteAction(HttpMethod $method, string $uri, Closure $action): Route {
        $route = new Route($uri, $action);
        $this->routes[$method->value][$uri] = $route;
        return $route;
    }

    final public function get(string $uri, Closure $action): Route {
        return $this->registerRouteAction(HttpMethod::GET, $uri, $action);
    }


    final public function post(string $uri, Closure $action): Route {
        return $this->registerRouteAction(HttpMethod::POST, $uri, $action);
    }

    final public function put(string $uri, Closure $action): Route {
        return $this->registerRouteAction(HttpMethod::PUT, $uri, $action);
    }

    final public function patch(string $uri, Closure $action): Route {
        return $this->registerRouteAction(HttpMethod::PATCH, $uri, $action);
    }

    final public function delete(string $uri, Closure $action): Route {
        return $this->registerRouteAction(HttpMethod::DELETE, $uri, $action);
    }
}
