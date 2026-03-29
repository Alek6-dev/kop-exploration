<?php

declare(strict_types=1);

namespace App\Performance\Application\Command\SavePerformance;

use App\Driver\Domain\Model\DriverInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class SaveDriverPerformanceCommand implements CommandInterface
{
    public function __construct(
        public SeasonInterface $season,
        public RaceInterface $race,
        public DriverInterface $driver,
        public ResultInterface $result,
        public string $qualificationPosition,
        public int $positionGain,
        public ?int $position,
        public ?string $sprintPosition = null,
    ) {
    }
}
