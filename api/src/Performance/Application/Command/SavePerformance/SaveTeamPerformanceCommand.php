<?php

declare(strict_types=1);

namespace App\Performance\Application\Command\SavePerformance;

use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Team\Domain\Model\TeamInterface;

/**
 * @implements CommandInterface<self>
 */
class SaveTeamPerformanceCommand implements CommandInterface
{
    public function __construct(
        public SeasonInterface $season,
        public RaceInterface $race,
        public TeamInterface $team,
        public DriverPerformanceInterface $driverPerformance1,
        public DriverPerformanceInterface $driverPerformance2,
        public ResultInterface $result,
    ) {
    }
}
