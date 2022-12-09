<?php

putenv('TZ=America/Cancun');
openlog("pandora_log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

require_once '../vendor/autoload.php';

use Pandora\Constants\ErrorResponse;
use Pandora\Exception\NotFoundException;
use Pandora\Routes\Router;
use Pandora\Server\Request;
use Pandora\Server\Response;
use Pandora\Server\ResponseError;
use Pandora\Server\ServerNative;

$router = new Router();

$router->get('/test', static function (Request $request) {
    return new Response(["message" => "GET /test OK"]);
});

$router->post('/test', static function (Request $request) {
    return new Response(["message" => "POST /test OK"]);
});

$router->put('/test', static function (Request $request) {
    return new Response(["message" => "PUT /test OK"]);
});

$router->patch('/test', static function (Request $request) {
    return new Response(["message" => "PATCH /test OK"]);
});

$router->delete('/test', static function (Request $request) {
    return new Response(["message" => "DELETE /test OK"]);
});

$server = new ServerNative();

try {
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->getAction();
    $response = $action($request);
} catch (NotFoundException $e) {
    try {
        $response = new Response(
            $e->getModelResponse(),
            ErrorResponse::NOT_FOUND->value
        );
    } catch (JsonException|Exception $e) {
        $error = new ResponseError($e);
        $response = $error->internalServerErrorResponse();
    }
} catch (Exception $e) {
    $error = new ResponseError($e);
    $response = $error->internalServerErrorResponse();
}

$server->sendResponse($response);

closelog();
