<?php

namespace Pandora\Tests\Server;

use JsonException;
use Pandora\Constants\HttpMethod;
use Pandora\Server\Request;
use Pandora\Server\Response;
use Pandora\Server\ServerMock;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase {
    /**
     * @throws JsonException
     */
    final public function test_json_response_is_constructed_correctly(): void {
        $data = ['data' => 'welcome'];
        $response = new Response(['data' => 'welcome']);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(json_encode($data, JSON_THROW_ON_ERROR), $response->getContent());
    }

    final public function test_text_response_is_constructed_correctly(): void {
        $data = "Hola mundo";
        $response = Response::text($data);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals($data, $response->getContent());
        $this->assertEquals('text/plain', $response->getHeaders()['content-type']);
    }

    final public function test_redirect_response_is_constructed_correctly(): void {
        $response = Response::redirect("https://google.com");
        $this->assertEquals(302, $response->getStatus());
        $this->assertEquals('https://google.com', $response->getHeaders()['location']);
    }

    final public function test_prepare_method_removes_content_headers_if_there_is_no_content(): void {
        $response = new Response();
        $this->assertEquals(204, $response->getStatus());
    }

    /**
     * @throws JsonException
     * @runInSeparateProcess
     */
    final public function test_prepare_method_adds_content_length_header_if_there_is_content(): void {
        $data = ['data' => 'welcome'];
        $response = new Response($data);
        $server = new ServerMock("/test", HttpMethod::GET);
        $server->sendResponse($response);
        $this->assertEquals(200, $response->getStatus());
        $this->assertArrayHasKey('content-length', $response->getHeaders());
    }
}
