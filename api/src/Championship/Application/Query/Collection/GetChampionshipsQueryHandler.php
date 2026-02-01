<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\Collection;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetChampionshipsQueryHandler
{
    public function __construct(private ChampionshipRepositoryInterface $repository)
    {
    }

    public function __invoke(GetChampionshipsQuery $query): ChampionshipRepositoryInterface
    {
        $statusFilters = ChampionshipStatusEnum::isActiveStatus();
        if (!$query->isActive) {
            $statusFilters = ChampionshipStatusEnum::isOverStatus();
        }

        $repository = $this->repository;
        foreach ($statusFilters as $statusFilter) {
            $repository = $repository->withStatus($statusFilter, true);
        }

        $repository = $repository->withUser($query->user);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
