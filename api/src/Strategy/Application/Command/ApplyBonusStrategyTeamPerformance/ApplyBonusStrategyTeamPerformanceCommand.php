<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\ApplyBonusStrategyTeamPerformance;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;

/**
 * @implements CommandInterface<self>
 */
class ApplyBonusStrategyTeamPerformanceCommand implements CommandInterface
{
    public function __construct(
        public StrategyTeamPerformanceInterface $strategyTeamPerformance,
        public BonusApplicationInterface $bonusToApplied,
    ) {
    }
}
