<?php

declare(strict_types=1);

namespace App\Duel\Application\Query\Get;

use App\Duel\Domain\Exception\DuelException;
use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetDuelQueryHandler
{
    public function __construct(private DuelRepositoryInterface $repository)
    {
    }

    public function __invoke(GetDuelQuery $query): DuelInterface
    {
        /** @var DuelInterface $model */
        $model = $this->repository
            ->withChampionship($query->championship)
            ->withRace($query->race)
            ->withPlayer($query->player)
            ->first()
        ;

        if (!$model) {
            throw DuelException::noMatch($query->player->getUuid(), $query->race->getUuid(), $query->championship->getUuid());
        }

        return $model;
    }
}
