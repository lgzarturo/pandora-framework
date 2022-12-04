<?php

require "./router.php";

$router = new Router();

$router->get('/test', function () {
    return "GET /test OK";
});

$router->post('/test', function () {
    return "POST /test OK";
});


var_dump($router);
