<?php

declare(strict_types=1);

namespace App\Duel\Domain\Model;

use App\Performance\Domain\Model\DriverPerformanceInterface;

interface DuelDriverPerformanceInterface extends DriverPerformanceInterface
{
    public function getDuel(): ?DuelInterface;

    public function setDuel(DuelInterface $duel): static;

    public function getPerformanceReference(): ?DriverPerformanceInterface;

    public function setPerformanceReference(DriverPerformanceInterface $performance): static;
}
