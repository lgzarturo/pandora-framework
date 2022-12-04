<?php

require "./router.php";

$router = new Router();

$router->get('/test', function () {
    return "GET /test OK";
});

$router->post('/test', function () {
    return "POST /test OK";
});

try {
    $action = $router->resolve();
    print($action());
} catch (NotFoundException $e) {
    print("Not found");
    http_response_code(404);
}
