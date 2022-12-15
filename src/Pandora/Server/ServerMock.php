<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

class ServerMock implements Server {
    public function __construct(public string $uri, public HttpMethod $method) {
    }

    final public function getRequest(): Request
    {
        return (new Request())
            ->setUri($this->uri)
            ->setMethod($this->method)
            ->setBody([])
            ->setQueryString([]);
    }

    final public function sendResponse(Response $response): void {
        $response->prepare();
        http_response_code($response->getStatus());
        foreach ($response->getHeaders() as $header => $value) {
            header("{$header}: {$value}");
        }
    }

}
