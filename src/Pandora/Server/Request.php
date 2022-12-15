<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;
use Pandora\Routes\Route;

class Request {
    private string $uri;
    private Route $route;
    private HttpMethod $method;
    private array $body;
    private array $queryString;

    final public function getUri(): string {
        return $this->uri;
    }


    final public function setUri(string $uri): self {
        $this->uri = $uri;
        return $this;
    }

    final public function getRoute(): Route
    {
        return $this->route;
    }

    final public function setRoute(Route $route): self
    {
        $this->route = $route;
        return $this;
    }

    final public function getMethod(): HttpMethod {
        return $this->method;
    }

    final public function setMethod(HttpMethod $method): self {
        $this->method = $method;
        return $this;
    }

    final public function getBody(): array {
        return $this->body;
    }

    final public function setBody(array $body): self {
        $this->body = $body;
        return $this;
    }

    final public function getQueryString(): array {
        return $this->queryString;
    }
    final public function setQueryString(array $params): self {
        $this->queryString = $params;
        return $this;
    }

}
