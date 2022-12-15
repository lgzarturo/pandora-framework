<?php

namespace Pandora\Server;

use JsonException;
use Pandora\Constants\SuccessResponse;

class Response {
    private int|null $status = null;
    private array $headers = [];
    private string|null $content = null;
    private array $model = [];


    /**
     * @throws JsonException
     */
    public function __construct(array|null $model = null, int|null $status = null) {
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

    /**
     * @throws JsonException
     */
    public static function json(array $data): self {
        return new self($data);
    }

    public static function text(string $data): self {
        return (new self())
            ->setContentType("text/plain")
            ->setStatus(SuccessResponse::OK->value)
            ->setContent($data);
    }

    final public function setContentType(string $contentType): self {
        $this->setHeader("Content-Type", $contentType);
        return $this;
    }

    final public function setHeader(string $header, string $value): self {
        $this->headers[strtolower($header)] = $value;
        return $this;
    }

    public static function redirect(string $url): self {
        return (new self())
            ->setStatus(302)
            ->setHeader("Location", $url);
    }

    final public function getStatus(): int {
        return $this->status;
    }

    final public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

    final public function getHeaders(): array {
        return $this->headers;
    }

    final public function prepare(): void {
        header("Content-Type: None");
        header_remove("Content-Type");
        if ($this->getContent() === null) {
            $this->removeHeader("Content-Type");
            $this->removeHeader("Content-Length");
        } else {
            if (count($this->model) === 0) {
                $this->setContentType("text/plain");
            } else {
                $this->setContentType("application/json");
            }
            $this->setHeader("Content-Length", strlen($this->getContent()));
        }
    }

    final public function getContent(): string|null {
        return $this->content;
    }

    final public function setContent(string|null $content): self {
        $this->content = $content;
        return $this;
    }

    final public function removeHeader(string $header): void {
        unset($this->headers[strtolower($header)]);
    }
}
