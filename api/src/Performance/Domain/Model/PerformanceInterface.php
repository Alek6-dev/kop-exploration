<?php

declare(strict_types=1);

namespace App\Performance\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface PerformanceInterface extends Idable, Uuidable, Timestampable
{
    public function getScore(): ?int;

    public function setScore(?int $score): static;

    public function getPosition(): ?int;

    public function setPosition(?int $position): static;

    public function getPoints(): ?int;

    public function setPoints(?int $points): static;
}
