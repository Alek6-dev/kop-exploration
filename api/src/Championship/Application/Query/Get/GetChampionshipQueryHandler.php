<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\Get;

use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetChampionshipQueryHandler
{
    public function __construct(private ChampionshipRepositoryInterface $repository)
    {
    }

    public function __invoke(GetChampionshipQuery $query): ChampionshipInterface
    {
        /** @var ?ChampionshipInterface $model */
        $model = $this->repository->getByUuid($query->uuid);

        if (!$model) {
            throw ChampionshipException::notFound($query->uuid);
        }

        return $model;
    }
}
