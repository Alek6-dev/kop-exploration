<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\ApplyBonusDuelDriverPerformance;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Duel\Domain\Model\DuelDriverPerformanceInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ApplyBonusDuelDriverPerformanceCommand implements CommandInterface
{
    public function __construct(
        public DuelDriverPerformanceInterface $duelDriverPerformance,
        public BonusApplicationInterface $bonusToApplied,
    ) {
    }
}
