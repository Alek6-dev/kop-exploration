<?php

declare(strict_types=1);

namespace App\Strategy\Application\Query\Get;

use App\Shared\Application\Query\AsQueryHandler;
use App\Strategy\Domain\Exception\StrategyException;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;

#[AsQueryHandler]
final readonly class GetStrategyQueryHandler
{
    public function __construct(private StrategyRepositoryInterface $repository)
    {
    }

    public function __invoke(GetStrategyQuery $query): StrategyInterface
    {
        /** @var ?StrategyInterface $strategy */
        $strategy = $this->repository
            ->withChampionship($query->championship)
            ->withRace($query->race)
            ->withPlayer($query->player)
            ->first()
        ;

        if (!$strategy) {
            throw StrategyException::noMatch($query->player->getUuid(), $query->race->getUuid(), $query->championship->getUuid());
        }

        return $strategy;
    }
}
