<?php

declare(strict_types=1);

namespace App\Performance\Application\Query\GetTeamPerformance;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Team\Domain\Model\TeamInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetTeamPerformanceQuery implements QueryInterface
{
    public function __construct(
        public SeasonInterface $season,
        public RaceInterface $race,
        public TeamInterface $team,
    ) {
    }
}
