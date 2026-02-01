<?php

declare(strict_types=1);

namespace App\Strategy\Domain\Model;

use App\Performance\Domain\Model\DriverPerformanceInterface;

interface StrategyDriverPerformanceInterface extends DriverPerformanceInterface
{
    public function getStrategy(): ?StrategyInterface;

    public function setStrategy(StrategyInterface $strategy): static;

    public function getPerformanceReference(): DriverPerformanceInterface;

    public function setPerformanceReference(DriverPerformanceInterface $performance): static;
}
