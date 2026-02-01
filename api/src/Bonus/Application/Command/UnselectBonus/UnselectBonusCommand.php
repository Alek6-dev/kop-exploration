<?php

declare(strict_types=1);

namespace App\Bonus\Application\Command\UnselectBonus;

use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements CommandInterface<self>
 */
class UnselectBonusCommand implements CommandInterface
{
    public function __construct(
        public DuelInterface|StrategyInterface $entity,
        public PlayerInterface $player,
    ) {
    }
}
