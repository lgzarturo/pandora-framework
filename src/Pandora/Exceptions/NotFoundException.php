<?php

namespace Pandora\Exception;

use Exception;
use JsonException;
use Throwable;

class NotFoundException extends Exception
{
    public function __construct(
        string         $message,
        int            $code = 0,
        Throwable|null $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->getModelResponse(), JSON_THROW_ON_ERROR);
    }

    final public function getModelResponse(): array
    {
        return ["code" => $this->code, "message" => $this->message];
    }
}
