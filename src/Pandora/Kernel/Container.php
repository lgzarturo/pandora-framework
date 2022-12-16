<?php

namespace Pandora\Kernel;

class Container {
    private static array $instances = [];

    final public static function singleton(string $class): mixed {
        if (!array_key_exists($class, self::$instances)) {
            self::$instances[$class] = new $class();
        }
        return self::$instances[$class];
    }

    final public static function resolve(string $class): mixed {
        return self::$instances[$class] ?? null;
    }
}
