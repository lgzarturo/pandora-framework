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
}
