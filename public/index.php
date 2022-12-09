<?php

require_once '../vendor/autoload.php';

use Pandora\Constants\ErrorResponse;
use Pandora\Exception\NotFoundException;
use Pandora\Routes\Router;
use Pandora\Server\Request;
use Pandora\Server\ServerNative;

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
        $route = $router->resolve(new Request(new ServerNative()));
    }
    if (isset($route)) {
        $action = $route->getAction();
        print($action());
    } else {
        print("Error del servidor, intentar de nuevo");
        http_response_code(ErrorResponse::INTERNAL_SERVER_ERROR->value);
    }
} catch (NotFoundException $e) {
    print($e);
    http_response_code(ErrorResponse::NOT_FOUND->value);
}
