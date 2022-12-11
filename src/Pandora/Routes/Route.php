<?php

namespace Pandora\Routes;

use Closure;

class Route {
    private string $uri;
    private Closure $action;
    private string $regex;
    private array $params;

    public function __construct(string $uri, Closure $action) {
        $this->uri = $uri;
        $this->action = $action;
        $this->regex = preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '([a-zA-Z0-9_-]+)', $this->uri);
        $params = [];
        preg_match_all('/\{([a-zA-Z0-9_-]+)\}/', $uri, $params);
        $this->params = $params[1];
    }

    final public function matches(string $uri): bool {
        return preg_match("#^$this->regex/?$#", $uri);
    }

    final public function hasParameters(): bool {
        return count($this->params) > 0;
    }

    final public function parseParameters(string $uri): array {
        $args = [];
        preg_match("#^$this->regex$#", $uri, $args);
        return array_combine($this->params, array_splice($args, 1));
    }

    final public function getUri(): string {
        return $this->uri;
    }

    final public function getAction(): Closure {
        return $this->action;
    }
}
