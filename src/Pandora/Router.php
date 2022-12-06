<?php

namespace Pandora;

use Pandora\Constants\HttpMethod;
use Pandora\Constants\ErrorResponse;

use Pandora\Exception\NotFoundException;

class Router {
    protected array $routes = [];

    public function __construct() {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(string $method, string $uri) {
        $action = $this->routes[$method][$uri] ?? null;
        if ($action === null) {
            throw new NotFoundException("El recurso solicitado no existe!", ErrorResponse::NOT_FOUND->value);
        }
        return $action;
    }

    public function get(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::GET, $uri, $action);
    }

    public function post(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::POST, $uri, $action);
    }

    public function put(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::PUT, $uri, $action);
    }

    public function patch(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::PATCH, $uri, $action);
    }

    public function delete(string $uri, callable $action): void {
        $this->resolveAction(HttpMethod::DELETE, $uri, $action);
    }

    private function resolveAction(HttpMethod $method, string $uri, callable $action): void {
        $this->routes[$method->value][$uri] = $action;
    }
}
