<?php

namespace Pharrow\Runner;

use Throwable;

final class StackTraceFilter
{
    private static string $frameworkSrcDir;

    public static function setFrameworkSrcDir(): string
    {
        return self::$frameworkSrcDir ??= realpath(__DIR__ . '/../') . '/';
    }

    public static function locate(Throwable $throwable): array
    {
        foreach ($throwable->getTrace() as $frame) {
            if (!isset($frame['file'])) {
                continue;
            }
            if (str_starts_with($frame['file'], self::setFrameworkSrcDir())) {
                continue;
            }
            return ['file' => $frame['file'], 'line' => $frame['line'] ?? 0];
        }
        return ['file' => $throwable->getFile(), 'line' => $throwable->getLine()];
    }
}
