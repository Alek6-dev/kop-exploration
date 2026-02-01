<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface HasMinValue
{
    public function getMinValue(): ?int;

    public function setMinValue(?int $minValue): static;
}
