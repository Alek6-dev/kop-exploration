<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface Labelable extends \Stringable
{
    public function getLabel(): ?string;

    public function setLabel(?string $label): void;
}
