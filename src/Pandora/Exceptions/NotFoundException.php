<?php

namespace Pandora\Exception;

class NotFoundException extends \Exception {
    public function __construct(string $message, $code = 0, \Throwable|null $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string {
        return "{\"code\": {$this->code}, \"message\": \"{$this->message}\"}\n";
    }
}
