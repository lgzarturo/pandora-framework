<?php

namespace Pandora;

use Pandora\Constants\HttpMethod;
use Pandora\Constants\ErrorResponse;

use Pandora\Exception\NotFoundException;

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
    final public function resolve(string $method, string $uri): callable {
        $action = $this->routes[$method][$uri] ?? null;
        if ($action === null) {
            throw new NotFoundException("El recurso solicitado no existe!", ErrorResponse::NOT_FOUND->value);
        }
        return $action;
    }

    final public function get(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::GET, $uri, $action);
    }

    final public function post(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::POST, $uri, $action);
    }

    final public function put(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::PUT, $uri, $action);
    }

    final public function patch(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::PATCH, $uri, $action);
    }

    final public function delete(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::DELETE, $uri, $action);
    }

    private function resolveAction(HttpMethod $method, string $uri, callable $action): void {
        $this->routes[$method->value][$uri] = $action;
    }
}
