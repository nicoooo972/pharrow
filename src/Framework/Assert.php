<?php

declare(strict_types=1);

namespace PHPUnit\Framework;

abstract class Assert
{
    private static int $count = 0;

    final public static function getCount(): int
    {
        return self::$count;
    }

    final public static function resetCount(): void
    {
        self::$count = 0;
    }

    final public static function assertTrue(bool $bool, string $message = '')
    {
        self::$count++;
        if (!$bool) {
            throw new AssertionFailedError(
                $message !== '' ? $message : "Failed asserting that value is true."
            );
        }
    }

    final public static function assertSame(mixed $value, mixed $expected, string $message = ''): void
    {
        self::$count++;
        if ($expected !== $value) {
            throw new AssertionFailedError(
                $message !== '' ? $message : "Failed asserting that two values are equal."
            );
        }
    }
}
