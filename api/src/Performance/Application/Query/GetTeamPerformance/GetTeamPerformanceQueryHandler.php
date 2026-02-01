<?php

declare(strict_types=1);

namespace App\Performance\Application\Query\GetTeamPerformance;

use App\Performance\Domain\Exception\PerformanceException;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Performance\Domain\Repository\TeamPerformanceRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetTeamPerformanceQueryHandler
{
    public function __construct(private TeamPerformanceRepositoryInterface $repository)
    {
    }

    public function __invoke(GetTeamPerformanceQuery $query): TeamPerformanceInterface
    {
        /** @var ?TeamPerformanceInterface $model */
        $model = $this->repository
            ->withSeason($query->season)
            ->withRace($query->race)
            ->withTeam($query->team)
            ->first()
        ;

        if (!$model) {
            throw PerformanceException::notFound('team', $query->season->getName(), $query->race->getName(), $query->team->getName());
        }

        return $model;
    }
}
