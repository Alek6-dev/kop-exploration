<?php

declare(strict_types=1);

namespace App\Race\Application\Query\Collection;

use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetRacesToAddToChampionshipQueryHandler
{
    /**
     * In days.
     */
    public const string INTERVAL_LIMIT_STRATEGY_DATE = '1';

    public function __construct(private RaceRepositoryInterface $repository)
    {
    }

    public function __invoke(GetRacesToAddToChampionshipQuery $query): RaceRepositoryInterface
    {
        return $this->repository
            ->withLimitStrategyDateGreaterThan(
                $query->season,
                (new \DateTimeImmutable())->add(new \DateInterval(sprintf('P%sD', self::INTERVAL_LIMIT_STRATEGY_DATE)))
            )
            ->orderByDate('ASC')
            ->limit($query->limit)
        ;
    }
}
