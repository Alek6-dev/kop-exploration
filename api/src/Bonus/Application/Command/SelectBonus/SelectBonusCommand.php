<?php

declare(strict_types=1);

namespace App\Bonus\Application\Command\SelectBonus;

use App\Bonus\Domain\Model\BonusInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements CommandInterface<self>
 */
class SelectBonusCommand implements CommandInterface
{
    public function __construct(
        public BonusInterface $bonus,
        public PlayerInterface $player,
        public DuelInterface|StrategyInterface $entity,
        public ?PlayerInterface $target = null,
    ) {
    }
}
