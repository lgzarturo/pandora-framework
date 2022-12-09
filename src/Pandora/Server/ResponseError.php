<?php

namespace Pandora\Server;

use Exception;
use Pandora\Constants\ErrorResponse;

class ResponseError
{

    public function __construct(public Exception $exception)
    {
    }

    final public function internalServerErrorResponse(): Response
    {
        syslog(LOG_ERR, "{$this->exception->getMessage()} {$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
        $response = new Response();
        $response->setHeader("Content-Type", "plain/text");
        $response->setContent("Error interno del servidor");
        $response->setStatus(ErrorResponse::INTERNAL_SERVER_ERROR->value);
        return $response;
    }
}
