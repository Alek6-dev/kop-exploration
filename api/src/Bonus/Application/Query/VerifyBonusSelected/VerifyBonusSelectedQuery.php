<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\VerifyBonusSelected;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements QueryInterface<BonusApplicationInterface>
 */
final readonly class VerifyBonusSelectedQuery implements QueryInterface
{
    public function __construct(
        public PlayerInterface $player,
        public StrategyInterface|DuelInterface $entity,
    ) {
    }
}
