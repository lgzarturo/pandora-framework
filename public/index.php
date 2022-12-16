<?php

putenv('TZ=America/Cancun');
openlog("pandora_log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

require_once '../vendor/autoload.php';

use Pandora\Kernel\App;
use Pandora\Middlewares\Authentication;
use Pandora\Routes\Route;
use Pandora\Server\Request;
use Pandora\Server\Response;

$app = App::bootstrap();

$app->router->get('/', static function (Request $request) {
    return Response::redirect("https://google.com");
});

$app->router->get('/test/{param}', static function (Request $request) {
    return Response::json($request->getRouteParams());
});

$app->router->get('/test/{param}/id/{id}', static function (Request $request) {
    return Response::json($request->getRouteParams('id'));
});

$app->router->get('/test', static function (Request $request) {
    return Response::json(["message" => "GET /test OK"]);
});

$app->router->post('/test', static function (Request $request) {
    return Response::json(["message" => "POST /test OK"]);
});

$app->router->put('/test', static function (Request $request) {
    return Response::json(["message" => "PUT /test OK"]);
});

$app->router->patch('/test', static function (Request $request) {
    return Response::json(["message" => "PATCH /test OK"]);
});

$app->router->delete('/test', static function (Request $request) {
    return Response::json(["message" => "DELETE /test OK"]);
});

$action = static fn (Request $request) => Response::json(["message" => "ok"]);
Route::get('/middlewares', $action)->setMiddlewares([Authentication::class]);

$app->run();

closelog();
