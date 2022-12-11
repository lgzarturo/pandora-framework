<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

class ServerMock implements Server {
    public function __construct(public string $uri, public HttpMethod $method) {
    }

    final public function getUri(): string {
        return $this->uri;
    }

    final public function getMethod(): HttpMethod {
        return $this->method;
    }

    final public function getBody(): array {
        return [];
    }

    final public function getQueryString(): array {
        return [];
    }

    final public function sendResponse(Response $response): void {
        // TODO: Implement sendResponse() method.
    }
}
