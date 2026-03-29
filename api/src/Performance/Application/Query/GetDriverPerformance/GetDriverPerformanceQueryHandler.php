<?php

declare(strict_types=1);

namespace App\Performance\Application\Query\GetDriverPerformance;

use App\Performance\Domain\Exception\PerformanceException;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Repository\DriverPerformanceRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetDriverPerformanceQueryHandler
{
    public function __construct(private DriverPerformanceRepositoryInterface $repository)
    {
    }

    public function __invoke(GetDriverPerformanceQuery $query): DriverPerformanceInterface
    {
        /** @var ?DriverPerformanceInterface $model */
        $model = $this->repository
            ->withSeason($query->season)
            ->withRace($query->race)
            ->withDriver($query->driver)
            ->first()
        ;

        if (!$model) {
            throw PerformanceException::notFound('driver', $query->season->getName(), $query->race->getName(), $query->driver->getName());
        }

        return $model;
    }
}
