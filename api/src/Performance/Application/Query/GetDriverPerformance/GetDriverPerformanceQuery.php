<?php

declare(strict_types=1);

namespace App\Performance\Application\Query\GetDriverPerformance;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetDriverPerformanceQuery implements QueryInterface
{
    public function __construct(
        public SeasonInterface $season,
        public RaceInterface $race,
        public DriverInterface $driver,
    ) {
    }
}
