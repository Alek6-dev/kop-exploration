<?php

namespace App\Performance\Domain\Model;

interface HasBonusInterface
{
    public function getScoreWithBonus(): ?int;

    public function setScoreWithBonus(?int $score): static;
}
