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

    public function __construct() {
        $this->router = new Router();
        $this->server = new ServerNative();
        $this->request = $this->server->getRequest();
    }

    final public function run(): void {
        try {
            $route = $this->router->resolve($this->request);
            $this->request->setRoute($route);
            $action = $route->getAction();
            $response = $action($this->request);
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
