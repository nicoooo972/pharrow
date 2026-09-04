<?php

declare(strict_types=1);

namespace PHPUnit\Framework;

interface SelfDescribing
{
    public function toString(): string;
}
