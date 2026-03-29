<?php

declare(strict_types=1);

namespace App\Strategy\Application\Query\GetCollection;

use App\Shared\Application\Query\AsQueryHandler;
use App\Strategy\Domain\Exception\StrategyException;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;

#[AsQueryHandler]
final readonly class GetStrategyCollectionQueryHandler
{
    public function __construct(private StrategyRepositoryInterface $repository)
    {
    }

    public function __invoke(GetStrategyCollectionQuery $query): StrategyRepositoryInterface
    {
        $strategies = $this->repository
            ->withChampionship($query->championship)
            ->withRace($query->race)
            ->withOrderByScore()
        ;

        if (!$strategies->count()) {
            throw StrategyException::noResult($query->race->getUuid(), $query->championship->getUuid());
        }

        return $strategies;
    }
}
