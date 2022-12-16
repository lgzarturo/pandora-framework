<?php

namespace Pandora\Kernel;

use Exception;
use JsonException;
use Pandora\Exception\NotFoundException;
use Pandora\Routes\Router;
use Pandora\Server\Request;
use Pandora\Server\ResponseError;
use Pandora\Server\Server;
use Pandora\Server\ServerNative;

class App {
    public Request $request;
    public Router $router;
    public Server $server;

    public static function bootstrap(): self {
        $app = Container::singleton(self::class);
        assert($app instanceof self);
        $app->router = new Router();
        $app->server = new ServerNative();
        $app->request = $app->server->getRequest();
        return $app;
    }

    final public function run(): void {
        try {
            $response = $this->router->resolveResponse($this->request);
        } catch (NotFoundException $e) {
            try {
                $response = ResponseError::notFound($e);
            } catch (JsonException|Exception $e) {
                $response = ResponseError::serverError($e);
            }
        } catch (Exception $e) {
            $response = ResponseError::serverError($e);
        }

        $this->server->sendResponse($response);
    }
}
