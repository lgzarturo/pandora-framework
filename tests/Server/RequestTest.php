<?php

namespace Pandora\Tests\Server;

use Pandora\Constants\HttpMethod;
use Pandora\Server\Request;
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
}