<?php

declare(strict_types=1);

namespace App\Duel\Application\Query\GetCollection;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements QueryInterface<StrategyInterface[]>
 */
final readonly class GetDuelCollectionQuery implements QueryInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public RaceInterface $race,
    ) {
    }
}
