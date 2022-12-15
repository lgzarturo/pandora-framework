<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

interface Server {
    public function getRequest(): Request;
    public function sendResponse(Response $response): void;
}
