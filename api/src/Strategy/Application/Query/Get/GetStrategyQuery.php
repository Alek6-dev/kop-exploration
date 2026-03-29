<?php

declare(strict_types=1);

namespace App\Strategy\Application\Query\Get;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements QueryInterface<StrategyInterface>
 */
final readonly class GetStrategyQuery implements QueryInterface
{
    public function __construct(
        public PlayerInterface $player,
        public ChampionshipInterface $championship,
        public RaceInterface $race,
    ) {
    }
}
