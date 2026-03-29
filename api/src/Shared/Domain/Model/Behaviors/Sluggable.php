<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface Sluggable extends Labelable
{
    public function getSlug(): ?string;

    public function setSlug(?string $slug): void;
}
