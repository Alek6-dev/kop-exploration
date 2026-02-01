<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\ApplyBonusStrategyDriverPerformance;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;

/**
 * @implements CommandInterface<self>
 */
class ApplyBonusStrategyDriverPerformanceCommand implements CommandInterface
{
    public function __construct(
        public StrategyDriverPerformanceInterface $strategyDriverPerformance,
        public BonusApplicationInterface $bonusToApplied,
    ) {
    }
}
