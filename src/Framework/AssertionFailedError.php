<?php

declare(strict_types=1);

namespace PHPUnit\Framework;

class AssertionFailedError extends Exception implements SelfDescribing
{
    public function toString(): string
    {
        return $this->getMessage();
    }
}
