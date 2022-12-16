<?php

namespace Pandora\Server;

use Exception;
use JsonException;
use Pandora\Constants\ErrorMessage;
use Pandora\Constants\ErrorResponse;
use Pandora\Constants\ServerValue;
use Pandora\Exception\NotFoundException;

class ResponseError {
    public function __construct(public Exception $exception) {
        syslog(LOG_ERR, "{$this->exception->getMessage()} {$_SERVER[ServerValue::REMOTE_ADDR->value]} ({$_SERVER[ServerValue::HTTP_USER_AGENT->value]})");
    }

    /**
     * @throws JsonException
     */
    final public static function notFound(NotFoundException $exception): Response {
        new self($exception);
        return Response::json($exception->getModelResponse())
            ->setStatus(ErrorResponse::NOT_FOUND->value);
    }

    final public static function serverError(Exception $exception): Response {
        new self($exception);
        return Response::text(ErrorMessage::INTERNAL->value)
            ->setStatus(ErrorResponse::INTERNAL_SERVER_ERROR->value);
    }
}
