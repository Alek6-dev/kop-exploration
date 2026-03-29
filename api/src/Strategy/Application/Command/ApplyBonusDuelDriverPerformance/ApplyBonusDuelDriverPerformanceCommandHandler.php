<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\ApplyBonusDuelDriverPerformance;

use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class ApplyBonusDuelDriverPerformanceCommandHandler
{
    public function __invoke(ApplyBonusDuelDriverPerformanceCommand $command): DuelDriverPerformanceInterface
    {
        $duelDriverPerformance = $command->duelDriverPerformance;
        $bonusToApply = $command->bonusToApplied;

        return $duelDriverPerformance->setScoreWithBonus($bonusToApply->getBalanceAfter());
    }
}
