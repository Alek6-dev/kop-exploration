<?php

declare(strict_types=1);

namespace App\Duel\Application\Query\GetCollection;

use App\Duel\Domain\Exception\DuelException;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetDuelCollectionQueryHandler
{
    public function __construct(private DuelRepositoryInterface $repository)
    {
    }

    public function __invoke(GetDuelCollectionQuery $query): DuelRepositoryInterface
    {
        $strategies = $this->repository
            ->withChampionship($query->championship)
            ->withRace($query->race)
        ;

        if (!$strategies->count()) {
            throw DuelException::noResult($query->race->getUuid(), $query->championship->getUuid());
        }

        return $strategies;
    }
}
