<?php

declare(strict_types=1);

namespace App\Duel\Application\Query\Get;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<DuelInterface>
 */
final readonly class GetDuelQuery implements QueryInterface
{
    public function __construct(
        public PlayerInterface $player,
        public ChampionshipInterface $championship,
        public RaceInterface $race,
    ) {
    }
}
