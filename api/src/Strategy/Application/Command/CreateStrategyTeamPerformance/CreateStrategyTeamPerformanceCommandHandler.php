<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\CreateStrategyTeamPerformance;

use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Model\StrategyTeamPerformanceInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\StrategyTeamPerformance;

#[AsCommandHandler]
final readonly class CreateStrategyTeamPerformanceCommandHandler
{
    public function __invoke(CreateStrategyTeamPerformanceCommand $command): StrategyTeamPerformanceInterface
    {
        return (new StrategyTeamPerformance())
            ->setStrategy($command->strategy)
            ->setPerformanceReference($command->teamPerformance)
            ->setTeam($command->teamPerformance->getTeam())
            ->setPosition($command->teamPerformance->getPosition())
            ->setMultiplier($command->teamPerformance->getMultiplier())
            ->setScore($command->teamPerformance->getScore())
        ;
    }
}
