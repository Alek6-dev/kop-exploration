<?php

declare(strict_types=1);

namespace App\Strategy\Domain\Model;

use App\Performance\Domain\Model\TeamPerformanceInterface;

interface StrategyTeamPerformanceInterface extends TeamPerformanceInterface
{
    public function getStrategy(): ?StrategyInterface;

    public function setStrategy(StrategyInterface $strategy): static;

    public function getPerformanceReference(): ?TeamPerformanceInterface;

    public function setPerformanceReference(TeamPerformanceInterface $performance): static;
}
