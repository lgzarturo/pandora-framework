<?php

namespace Pandora\Server;

use Pandora\Constants\HttpMethod;
use Pandora\Constants\ServerValue;

class ServerNative implements Server {
    final public function getRequest(): Request {
        return (new Request())
            ->setUri(parse_url($_SERVER[ServerValue::REQUEST_URI->value], PHP_URL_PATH))
            ->setMethod(HttpMethod::from($_SERVER[ServerValue::REQUEST_METHOD->value]))
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
