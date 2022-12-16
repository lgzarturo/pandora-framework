<?php

namespace Pandora\Tests\Server;

use Pandora\Constants\HttpMethod;
use Pandora\Exception\NotFoundException;
use Pandora\Routes\Router;
use Pandora\Server\ServerMock;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {
    final public function test_request_returns_data_obtained_from_server_correctly(): void {
        $queryString = ['page' => 1];
        $body = ['data' => 'hello'];
        $server = new ServerMock("/test", HttpMethod::GET);
        $request = $server->getRequest();
        $request->setBody($body)->setQueryString($queryString);
        $this->assertEquals($queryString, $request->getQueryString());
        $this->assertEquals($body, $request->getBody());
    }

    final public function test_data_returns_value_if_key_is_given(): void {
        $body = ['data' => 'hello', 'content' => 'lorem2'];
        $server = new ServerMock("/test", HttpMethod::GET);
        $request = $server->getRequest();
        $request->setBody($body);
        $data = $request->getBody('data');
        $this->assertEquals(['data' => 'hello'], $data);
    }

    final public function test_data_returns_empty_array_if_key_not_exists(): void {
        $body = ['content' => 'lorem2'];
        $server = new ServerMock("/test", HttpMethod::GET);
        $request = $server->getRequest();
        $request->setBody($body);
        $data = $request->getBody('data');
        $this->assertEquals([], $data);
    }

    final public function test_query_returns_value_if_key_is_given(): void {
        $queryString = ['page' => 1, 'offset' => 20, 'order' => 'id,asc'];
        $server = new ServerMock("/test", HttpMethod::GET);
        $request = $server->getRequest();
        $request->setQueryString($queryString);
        $data = $request->getQueryString('page');
        $this->assertEquals(['page' => 1], $data);
    }

    final public function test_query_returns_empty_array_if_key_not_exists(): void {
        $queryString = ['offset' => 20, 'order' => 'id,asc'];
        $server = new ServerMock("/test", HttpMethod::GET);
        $request = $server->getRequest();
        $request->setQueryString($queryString);
        $data = $request->getQueryString('page');
        $this->assertEquals([], $data);
    }

    /**
     * @throws NotFoundException
     */
    final public function test_route_parameters_returns_value_if_key_is_given(): void {
        $router = new Router();
        $router->get('/test/{id}/data/{param}', static fn () => "test");
        $server = new ServerMock("/test/1/data/example", HttpMethod::GET);
        $request = $server->getRequest();
        $route = $router->resolveRoute($request);
        $request->setRoute($route);
        $data = $request->getRouteParams('id');
        $this->assertEquals(['id' => 1], $data);
    }

    /**
     * @throws NotFoundException
     */
    final public function test_route_parameters_returns_all_params(): void {
        $router = new Router();
        $router->get('/test/{id}/data/{param}', static fn () => "test");
        $server = new ServerMock("/test/1/data/example", HttpMethod::GET);
        $request = $server->getRequest();
        $route = $router->resolveRoute($request);
        $request->setRoute($route);
        $data = $request->getRouteParams();
        $this->assertEquals(['id' => 1, 'param' => 'example'], $data);
    }
}
