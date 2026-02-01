<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface HasColor
{
    public function getColor(): ?string;

    public function setColor(?string $color): static;
}
