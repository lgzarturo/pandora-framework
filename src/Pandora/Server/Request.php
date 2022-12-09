<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

class Request
{
    private string $uri;
    private HttpMethod $method;
    private array $body;
    private array $queryString;

    public function __construct(Server $server)
    {
        $this->uri = $server->getUri();
        $this->method = $server->getMethod();
        $this->body = $server->getBody();
        $this->queryString = $server->getQueryString();
    }

    final public function getUri(): string
    {
        return $this->uri;
    }

    final public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    final public function getBody(): array
    {
        return $this->body;
    }

    final public function getQueryString(): array
    {
        return $this->queryString;
    }
}
