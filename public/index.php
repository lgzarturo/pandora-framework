<?php

require_once '../vendor/autoload.php';

use Pandora\Constants\ErrorResponse;
use Pandora\Exception\NotFoundException;
use Pandora\Router;

$router = new Router();

$router->get('/test', static function () {
    return "GET /test OK";
});

$router->post('/test', static function () {
    return "POST /test OK";
});

$router->put('/test', static function () {
    return "PUT /test OK";
});

$router->patch('/test', static function () {
    return "PATCH /test OK";
});

$router->delete('/test', static function () {
    return "DELETE /test OK";
});

try {
    if ($_SERVER !== null) {
        $action = $router->resolve(
            method: $_SERVER["REQUEST_METHOD"],
            uri: $_SERVER["REQUEST_URI"]
        );
    }
    if (isset($action)) {
        print($action());
    } else {
        print("Error del servidor, intentar de nuevo");
        http_response_code(ErrorResponse::INTERNAL_SERVER_ERROR->value);
    }
} catch (NotFoundException $e) {
    print($e);
    http_response_code(ErrorResponse::NOT_FOUND->value);
}
