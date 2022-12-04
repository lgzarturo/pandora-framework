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

    public function resolve() {
        $action = $this->routes[$_SERVER["REQUEST_METHOD"]][$_SERVER["REQUEST_URI"]] ?? null;
        if (is_null($action)) {
            throw new NotFoundException("El recurso solicitado no existe!", ErrorResponse::NOT_FOUND->value);
        }
        return $action;
    }

    public function get(string $uri, callable $action) {
        $this->resolveAction(HttpMethod::GET, $uri, $action);
    }

    public function post(string $uri, callable $action) {
        $this->resolveAction(HttpMethod::POST, $uri, $action);
    }

    public function put(string $uri, callable $action) {
        $this->resolveAction(HttpMethod::PUT, $uri, $action);
    }

    public function patch(string $uri, callable $action) {
        $this->resolveAction(HttpMethod::PATCH, $uri, $action);
    }

    public function delete(string $uri, callable $action) {
        $this->resolveAction(HttpMethod::DELETE, $uri, $action);
    }

    private function resolveAction(HttpMethod $method, string $uri, callable $action) {
        $this->routes[$method->value][$uri] = $action;
    }
}
