<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\CreateStrategyDriverPerformance;

use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateStrategyDriverPerformanceCommand implements CommandInterface
{
    public function __construct(
        public StrategyInterface $strategy,
        public DriverPerformanceInterface $driverPerformance,
        public ?QualificationPositionPointEnum $qualificationPoints = null,
        public ?RacePositionPointEnum $racePoints = null,
        public ?SprintPositionPointEnum $sprintPoints = null,
        public ?int $positionGain = null,
    ) {
    }
}
