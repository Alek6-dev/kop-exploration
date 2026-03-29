<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\CreateStrategyTeamPerformance;

use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateStrategyTeamPerformanceCommand implements CommandInterface
{
    public function __construct(
        public StrategyInterface $strategy,
        public TeamPerformanceInterface $teamPerformance,
    ) {
    }
}
