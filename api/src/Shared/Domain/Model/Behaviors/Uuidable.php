<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface Uuidable
{
    public function getUuid(): string;
}
