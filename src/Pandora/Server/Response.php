<?php

namespace Pandora\Server;

use JsonException;
use Pandora\Constants\SuccessResponse;

class Response
{
    private int|null $status = null;
    private array $headers = [];
    private string|null $content = null;
    private array $model = [];


    /**
     * @throws JsonException
     */
    public function __construct(array|null $model = null, int|null $status = null)
    {
        if ($model !== null) {
            $this->model = $model;
        }
        if ($status !== null) {
            $this->status = $status;
        }
        if ($this->status === null) {
            $this->status = SuccessResponse::OK->value;
        }
        if ($model !== null && count($model) > 0) {
            $this->setContent(json_encode($model, JSON_THROW_ON_ERROR));
        } else {
            $this->status = SuccessResponse::NO_CONTENT->value;
        }
    }


    final public function getStatus(): int
    {
        return $this->status;
    }

    final public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    final public function getHeaders(): array
    {
        return $this->headers;
    }

    final public function prepare(): void
    {
        header("Content-Type: None");
        header_remove("Content-Type");
        if ($this->getContent() === null) {
            $this->removeHeader("Content-Type");
            $this->removeHeader("Content-Length");
        } else {
            if (count($this->model) === 0) {
                $this->setHeader("Content-Type", "text/plain");
            } else {
                $this->setHeader("Content-Type", "application/json");
            }
            $this->setHeader("Content-Length", strlen($this->getContent()));
        }
    }

    final public function getContent(): string|null
    {
        return $this->content;
    }

    final public function setContent(string|null $content): void
    {
        $this->content = $content;
    }

    final public function removeHeader(string $header): void
    {
        unset($this->headers[strtolower($header)]);
    }

    final public function setHeader(string $header, string $value): void
    {
        $this->headers[strtolower($header)] = $value;
    }
}
