<?php

require "./router.php";

$router = new Router();

$router->get('/test', function () {
    return "GET /test OK";
});

$router->post('/test', function () {
    return "POST /test OK";
});

$router->put('/test', function () {
    return "PUT /test OK";
});

$router->patch('/test', function () {
    return "PATCH /test OK";
});

$router->delete('/test', function () {
    return "DELETE /test OK";
});

try {
    $action = $router->resolve();
    print($action());
} catch (NotFoundException $e) {
    print("Not found");
    http_response_code(404);
}
