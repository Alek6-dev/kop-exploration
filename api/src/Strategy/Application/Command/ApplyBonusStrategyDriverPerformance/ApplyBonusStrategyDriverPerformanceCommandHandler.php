<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\ApplyBonusStrategyDriverPerformance;

use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Model\StrategyDriverPerformanceInterface;

#[AsCommandHandler]
final readonly class ApplyBonusStrategyDriverPerformanceCommandHandler
{
    public const int MAIN_DRIVER_MULTIPLIER = 2;

    public function __invoke(ApplyBonusStrategyDriverPerformanceCommand $command): StrategyDriverPerformanceInterface
    {
        $strategyDriverPerformance = $command->strategyDriverPerformance;
        $bonusToApply = $command->bonusToApplied;

        return $strategyDriverPerformance->setScoreWithBonus($bonusToApply->getBalanceAfter());
    }
}
