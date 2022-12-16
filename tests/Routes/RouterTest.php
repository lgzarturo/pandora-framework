<?php

namespace Pandora\Tests\Routes;

use Closure;
use Pandora\Constants\HttpMethod;
use Pandora\Exception\NotFoundException;
use Pandora\Routes\Router;
use Pandora\Server\Request;
use Pandora\Server\Response;
use Pandora\Server\Server;
use Pandora\Server\ServerMock;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {
    /**
     * @throws NotFoundException
     */
    final public function test_resolve_basic_route_with_callback_action(): void {
        $uri = '/test';
        $action = static fn () => "test";
        $router = new Router();
        $router->get($uri, $action);
        $server = new ServerMock($uri, HttpMethod::GET);
        $request = $server->getRequest();
        $this->assertEquals($action, $router->resolveRoute($request)->getAction());
    }

    /**
     * @throws NotFoundException
     */
    final public function test_resolve_multiple_routes_with_callback(): void {
        $routes = [
            '/test' => static fn () => "test",
            '/foo' => static fn () => "foo",
            '/bar' => static fn () => "bar",
            '/fizz' => static fn () => "fizz",
            '/fizz/buzz' => static fn () => "fizzbuzz",
        ];
        $router = new Router();
        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }
        foreach ($routes as $uri => $action) {
            $server = new ServerMock($uri, HttpMethod::GET);
            $request = $server->getRequest();
            $this->assertEquals($action, $router->resolveRoute($request)->getAction());
        }
    }

    /**
     * @throws NotFoundException
     */
    final public function test_resolve_multiple_routes_for_different_method_with_callback(): void {
        $routes = [
            [HttpMethod::GET, "/test", static fn () => "test"],
            [HttpMethod::POST, "/test", static fn () => "post test"],
            [HttpMethod::PUT, "/test", static fn () => "put test"],
            [HttpMethod::PATCH, "/test", static fn () => "patch test"],
            [HttpMethod::DELETE, "/test", static fn () => "delete test"],
            [HttpMethod::GET, "/books", static fn () => "books"],
            [HttpMethod::POST, "/items/create", static fn () => "create item"],
            [HttpMethod::PUT, "/category/programming/post/1", static fn () => "update post by category"],
            [HttpMethod::PATCH, "/fizz/buzz/19109", static fn () => "patch fizz buzz"],
            [HttpMethod::DELETE, "/users/199", static fn () => "delete user by id"],
        ];
        $router = new Router();
        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method->value)}($uri, $action);
        }
        foreach ($routes as [$method, $uri, $action]) {
            $mockRequest = $this->mockServerRequest($uri, $method);
            $this->assertEquals($action, $router->resolveRoute($mockRequest)->getAction());
        }
    }

    /**
     * @throws NotFoundException
     */
    final public function test_run_middleware(): void {
        $first = new class {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                assert($response instanceof Response);
                $response->setHeader('x-test-mw-one', 'first-middleware');
                return $response;
            }
        };
        $second = new class {
            public function handle(Request $request, Closure $next): Response {
                $response = $next($request);
                assert($response instanceof Response);
                $response->setHeader('x-test-mw-two', 'second-middleware');
                return $response;
            }
        };
        $router = new Router();
        $uri = "/test";
        $expected = Response::text("test");
        $action = static fn ($request) => $expected;
        $router->get($uri, $action)
            ->setMiddlewares([$first, $second]);
        $response = $router->resolveResponse($this->mockServerRequest($uri, HttpMethod::GET));
        $this->assertEquals($expected, $response);
        $this->assertEquals('first-middleware', $response->getContentHeader('x-test-mw-one'));
        $this->assertEquals('second-middleware', $response->getContentHeader('x-test-mw-two'));
    }

    private function mockServerRequest(mixed $uri, mixed $method): Request {
        $request = (new Request())->setUri($uri)->setMethod($method);
        $mock = $this->getMockBuilder(Server::class)->getMock();
        $mock->method('getRequest')->willReturn($request);
        return $mock->getRequest();
    }
}
