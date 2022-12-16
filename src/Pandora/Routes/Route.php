<?php

namespace Pandora\Routes;

use Closure;
use Pandora\Kernel\App;
use Pandora\Kernel\Container;
use Pandora\Kernel\Middleware;

class Route {
    private string $uri;
    private Closure $action;
    private string $regex;
    private array $params;
    /* @var Middleware[] */
    private array $middlewares = [];

    public function __construct(string $uri, Closure $action) {
        $replacement = '([a-zA-Z0-9_-]+)';
        $pattern = '/\{'.$replacement.'\}/';
        $this->uri = $uri;
        $this->action = $action;
        $this->regex = preg_replace(
            $pattern,
            $replacement,
            $this->uri
        );
        $params = [];
        preg_match_all($pattern, $uri, $params);
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

    final public function getMiddlewares(): array {
        return $this->middlewares;
    }

    final public function setMiddlewares(array $middlewares): self {
        $this->middlewares = array_map(
            static fn ($middleware) => new $middleware(),
            $middlewares
        );
        return $this;
    }

    final public function hasMiddlewares(): bool {
        return count($this->middlewares) > 0;
    }

    final public static function get(string $uri, Closure $action): self {
        $app = Container::resolve(App::class);
        assert($app instanceof App);
        return $app->router->get($uri, $action);
    }
}
