<?php

declare(strict_types=1);

namespace App\Performance\Application\Command\Generate;

use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class GeneratePerformanceCommand implements CommandInterface
{
    public function __construct(
        public SeasonInterface $season,
        public RaceInterface $race,
    ) {
    }
}
