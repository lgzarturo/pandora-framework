<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;

class ServerNative implements Server {
    final public function getRequest(): Request {
        return (new Request())
            ->setUri(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))
            ->setMethod(HttpMethod::from($_SERVER["REQUEST_METHOD"]))
            ->setHeaders(getallheaders())
            ->setBody($_POST)
            ->setQueryString($_GET);
    }

    final public function sendResponse(Response $response): void {
        $response->prepare();
        http_response_code($response->getStatus());
        foreach ($response->getHeaders() as $header => $value) {
            header("{$header}: {$value}");
        }
        print($response->getContent());
    }
}
