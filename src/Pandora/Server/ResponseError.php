<?php

namespace Pandora\Server;

use Exception;
use JsonException;
use Pandora\Constants\ErrorResponse;
use Pandora\Exception\NotFoundException;

class ResponseError
{

    public function __construct(public Exception $exception)
    {
        syslog(LOG_ERR, "{$this->exception->getMessage()} {$_SERVER['REMOTE_ADDR']} ({$_SERVER['HTTP_USER_AGENT']})");
    }

    /**
     * @throws JsonException
     */
    final public static function notFound(NotFoundException $exception): Response
    {
        new self($exception);
        return Response::json($exception->getModelResponse())
            ->setStatus(ErrorResponse::NOT_FOUND->value);
    }

    final public static function serverError(Exception $exception): Response
    {
        new self($exception);
        return Response::text("Error interno del servidor")
            ->setStatus(ErrorResponse::INTERNAL_SERVER_ERROR->value);
    }
}
