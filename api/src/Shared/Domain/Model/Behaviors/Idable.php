<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface Idable
{
    public function getId(): ?int;
}
