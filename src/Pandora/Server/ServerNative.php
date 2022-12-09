<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

class ServerNative implements Server
{
    final public function getUri(): string
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    final public function getMethod(): HttpMethod
    {
        return HttpMethod::from($_SERVER["REQUEST_METHOD"]);
    }

    final public function getBody(): array
    {
        return $_POST;
    }

    final public function getQueryString(): array
    {
        return $_GET;
    }
}
