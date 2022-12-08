<?php

namespace Pandora\Tests;

use Pandora\Routes\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    final public function routes(): array
    {
        return [
            ['/'],
            ['/test'],
            ['/test/example'],
            ['/test/data/uri'],
            ['/test/data/uri/route'],
            ['/test/data/uri/route/posts/data'],
            ['/test/data/uri/info/data/comments'],
        ];
    }

    /**
     * @dataProvider routes
     * @param string $uri
     * @return void
     */
    final public function test_routes_without_parameters(string $uri): void
    {
        $route = new Route($uri, static fn() => "test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("{$uri}/extra/path"));
        $this->assertFalse($route->matches("/first/{$uri}/extra/path"));
        $this->assertFalse($route->matches("/path/{$uri}"));
        $this->assertFalse($route->matches("/custom/path"));
    }

    /**
     * @dataProvider routes
     * @param string $uri
     * @return void
     */
    final public function test_routes_without_parameters_and_end_slash(string $uri): void
    {
        $route = new Route($uri, static fn() => "test");
        $this->assertTrue($route->matches("{$uri}/"));
    }

    final public function routesWithParameters(): array
    {
        return [
            [
                '/posts/{postId}',
                '/posts/1',
                ['postId' => 1]
            ],
            [
                '/posts/{postId}/comments/{comment_id}',
                '/posts/61/comments/918',
                ['postId' => 61, "comment_id" => 918]
            ],
            [
                '/category/{category}/posts/{postId}/comments/{comment_id}/replies/comments/{reply}',
                '/category/programming/posts/1982/comments/1009827/replies/comments/title-reply',
                ['category' => 'programming', 'postId' => 1982, 'comment_id' => 1009827, 'reply' => 'title-reply']
            ]
        ];
    }

    /**
     * @dataProvider routesWithParameters
     * @param string $requested
     * @param string $uri
     * @return void
     */
    final public function test_routes_with_parameters(string $requested, string $uri): void
    {
        $route = new Route($requested, static fn() => "test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("{$uri}/extra/path"));
        $this->assertFalse($route->matches("/first/{$uri}/extra/path"));
        $this->assertFalse($route->matches("/path/{$uri}"));
        $this->assertFalse($route->matches("/custom/path"));
    }

    /**
     * @dataProvider routesWithParameters
     * @param string $requested
     * @param string $uri
     * @return void
     */
    final public function test_routes_with_parameters_and_ends_slash(string $requested, string $uri): void
    {
        $route = new Route($requested, static fn() => "test");
        $this->assertTrue($route->matches("{$uri}/"));
    }

    /**
     * @dataProvider routesWithParameters
     * @param string $requested
     * @param string $uri
     * @param array $params
     * @return void
     */
    final public function test_parse_all_parameters(string $requested, string $uri, array $params): void
    {
        $route = new Route($requested, static fn() => "test");
        $this->assertTrue($route->hasParameters());
        $this->assertEquals($params, $route->parseParameters($uri));
    }
}
