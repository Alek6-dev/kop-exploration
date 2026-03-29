<?php

namespace App\Shared\Domain\Model\Behaviors;

interface Stringifyable
{
    public function __toString(): string;
}
