<?php

declare(strict_types=1);

namespace App\Duel\Application\Command\CreateDuelDriverPerformance;

use App\Duel\Domain\Model\DuelInterface;
use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateDuelDriverPerformanceCommand implements CommandInterface
{
    public function __construct(
        public DuelInterface $duel,
        public DriverPerformanceInterface $driverPerformance,
        public ?QualificationPositionPointEnum $qualificationPoints = null,
        public ?RacePositionPointEnum $racePoints = null,
        public ?SprintPositionPointEnum $sprintPoints = null,
        public ?int $positionGain = null,
    ) {
    }
}
