<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\GetBonusToApplyCollection;

use App\Bonus\Domain\Model\BonusInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements QueryInterface<array<BonusInterface>>
 */
final readonly class GetBonusToApplyCollectionQuery implements QueryInterface
{
    public function __construct(
        public StrategyInterface|DuelInterface $entity,
        public PlayerInterface $player,
        public RaceInterface $race,
    ) {
    }
}
