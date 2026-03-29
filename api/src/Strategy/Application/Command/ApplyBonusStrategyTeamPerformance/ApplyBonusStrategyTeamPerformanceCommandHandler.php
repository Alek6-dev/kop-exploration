<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\ApplyBonusStrategyTeamPerformance;

use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;

#[AsCommandHandler]
final readonly class ApplyBonusStrategyTeamPerformanceCommandHandler
{
    public function __invoke(ApplyBonusStrategyTeamPerformanceCommand $command): StrategyTeamPerformanceInterface
    {
        $strategyTeamPerformance = $command->strategyTeamPerformance;
        $bonusToApply = $command->bonusToApplied;

        return $strategyTeamPerformance->setMultiplier($bonusToApply->getBalanceAfter());
    }
}
