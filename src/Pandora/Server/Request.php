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
    private array $headers = [];

    final public function getUri(): string {
        return $this->uri;
    }


    final public function setUri(string $uri): self {
        $this->uri = $uri;
        return $this;
    }

    final public function getRouteParams(string|null $key = null): array {
        return $this->getByKey($this->route->parseParameters($this->uri), $key);
    }

    final public function getRoute(): Route {
        return $this->route;
    }

    final public function setRoute(Route $route): self {
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

    final public function getBody(string|null $key = null): array {
        return $this->getByKey($this->body, $key);
    }

    final public function setBody(array $body): self {
        $this->body = $body;
        return $this;
    }

    final public function getQueryString(string|null $key = null): array {
        return $this->getByKey($this->queryString, $key);
    }
    final public function setQueryString(array $params): self {
        $this->queryString = $params;
        return $this;
    }

    final public function getHeaders(string|null $key = null): array {
        return $this->getByKey($this->headers, strtolower($key));
    }

    final public function getContentHeader(string $key): string {
        $header = $this->getHeaders($key);
        if (empty($header)) {
            return '';
        }
        $value = reset($header);
        if (!$value) {
            return '';
        }
        return $value;
    }

    final public function setHeaders(array $headers): self {
        foreach ($headers as $header => $value) {
            $this->headers[strtolower($header)] = $value;
        }
        return $this;
    }

    private function getByKey(array $params, string|null $key = null): array {
        if (isset($key)) {
            $value = $params[$key] ?? null;
            if ($value === null) {
                return [];
            }
            return [$key => $value];
        }
        return $params;
    }
}
