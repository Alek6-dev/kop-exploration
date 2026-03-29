<?php

declare(strict_types=1);

namespace App\Performance\Application\Command\SavePerformance;

use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Performance\Domain\Repository\TeamPerformanceRepositoryInterface;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class SaveTeamPerformanceCommandHandler
{
    public function __construct(private TeamPerformanceRepositoryInterface $repository)
    {
    }

    public function __invoke(SaveTeamPerformanceCommand $command): TeamPerformanceInterface
    {
        /** @var ?TeamPerformanceInterface $model */
        $model = $this->repository
            ->withRace($command->race)
            ->withSeason($command->season)
            ->withTeam($command->team)
            ->first()
        ;

        if (!$model) {
            $model = (new TeamPerformance())
                ->setRace($command->race)
                ->setSeason($command->season)
                ->setTeam($command->team)
            ;
        }

        $score = (int) $command->driverPerformance1->getQualificationPosition()
            + $command->driverPerformance1->getPosition()
            + (int) $command->driverPerformance2->getQualificationPosition()
            + $command->driverPerformance2->getPosition()
        ;

        $model
            ->setPosition($command->position ?? $model->getPosition())
            ->setScore($score)
            ->setResult($command->result)
        ;

        return $model;
    }
}
