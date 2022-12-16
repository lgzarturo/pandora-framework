<?php

namespace Pandora\Tests\Server;

use JsonException;
use Pandora\Constants\ContentType;
use Pandora\Constants\HeaderName;
use Pandora\Constants\HttpMethod;
use Pandora\Constants\SuccessResponse;
use Pandora\Server\Response;
use Pandora\Server\ServerMock;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase {
    /**
     * @throws JsonException
     */
    final public function test_json_response_is_constructed_correctly(): void {
        $data = ['data' => 'welcome'];
        $response = new Response($data);
        $this->assertEquals(SuccessResponse::OK->value, $response->getStatus());
        $this->assertEquals(json_encode($data, JSON_THROW_ON_ERROR), $response->getContent());
    }

    final public function test_text_response_is_constructed_correctly(): void {
        $data = "Hola mundo";
        $response = Response::text($data);
        $header = strtolower(HeaderName::CONTENT_TYPE->value);
        $this->assertEquals(SuccessResponse::OK->value, $response->getStatus());
        $this->assertEquals($data, $response->getContent());
        $this->assertEquals(ContentType::TEXT->value, $response->getHeaders()[$header]);
    }

    final public function test_redirect_response_is_constructed_correctly(): void {
        $url = "https://google.com";
        $response = Response::redirect($url);
        $header = strtolower(HeaderName::LOCATION->value);
        $this->assertEquals(SuccessResponse::REDIRECT->value, $response->getStatus());
        $this->assertEquals($url, $response->getHeaders()[$header]);
    }

    final public function test_prepare_method_removes_content_headers_if_there_is_no_content(): void {
        $response = new Response();
        $this->assertEquals(SuccessResponse::NO_CONTENT->value, $response->getStatus());
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
        $header = strtolower(HeaderName::CONTENT_LENGTH->value);
        $this->assertEquals(SuccessResponse::OK->value, $response->getStatus());
        $this->assertArrayHasKey($header, $response->getHeaders());
    }
}
