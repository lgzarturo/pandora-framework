<?php

namespace Pandora\Tests;

use Pandora\Constants\HttpMethod;
use Pandora\Exception\NotFoundException;
use Pandora\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

    /**
     * @throws NotFoundException
     */
    final public function test_resolve_basic_route_with_callback_action(): void {
        $uri = '/test';
        $action = static fn() => "test";
        $router = new Router();
        $router->get($uri, $action);
        $this->assertEquals($action, $router->resolve(HttpMethod::GET->value, $uri));
    }

    /**
     * @throws NotFoundException
     */
    final public function test_resolve_multiple_routes_with_callback(): void {
        $routes = [
            '/test' => static fn() => "test",
            '/foo' => static fn() => "foo",
            '/bar' => static fn() => "bar",
            '/fizz' => static fn() => "fizz",
            '/fizz/buzz' => static fn() => "fizzbuzz",
        ];
        $router = new Router();
        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }
        foreach ($routes as $uri => $action) {
            $this->assertEquals($action, $router->resolve(HttpMethod::GET->value, $uri));
        }
    }

    /**
     * @throws NotFoundException
     */
    final public function test_resolve_multiple_routes_for_different_method_with_callback(): void {
        $routes = [
            [HttpMethod::GET, "/test", static fn() => "test"],
            [HttpMethod::POST, "/test", static fn() => "post test"],
            [HttpMethod::PUT, "/test", static fn() => "put test"],
            [HttpMethod::PATCH, "/test", static fn() => "patch test"],
            [HttpMethod::DELETE, "/test", static fn() => "delete test"],
            [HttpMethod::GET, "/books", static fn() => "books"],
            [HttpMethod::POST, "/items/create", static fn() => "create item"],
            [HttpMethod::PUT, "/category/programming/post/1", static fn() => "update post by category"],
            [HttpMethod::PATCH, "/fizz/buzz/19109", static fn() => "patch fizz buzz"],
            [HttpMethod::DELETE, "/users/199", static fn() => "delete user by id"],
        ];
        $router = new Router();
        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method->value)}($uri, $action);
        }
        foreach ($routes as [$method, $uri, $action]) {
            $this->assertEquals($action, $router->resolve($method->value, $uri));
        }
    }
}
