<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

interface Server
{
    public function getUri(): string;

    public function getMethod(): HttpMethod;

    public function getBody(): array;

    public function getQueryString(): array;
}
