<?php

namespace Pandora\Server;

class Header
{
    final public static function getByKey(array $headers, string|null $key = null): array {
        if (isset($key)) {
            $value = $headers[$key] ?? null;
            if ($value === null) {
                return [];
            }
            return [$key => $value];
        }
        return $headers;
    }

    final public static function getContentHeaderByKey(array $headers, string|null $key = null): string {
        $header = self::getByKey($headers, $key);
        return self::getContentHeader($header);
    }

    final public static function getContentHeader(array $header): string {
        if (empty($header)) {
            return "";
        }
        $value = reset($header);
        if (!$value) {
            return "";
        }
        return $value;
    }
}
