<?php

declare(strict_types=1);

namespace App\Team\Application\Query\GetAvailableTeamsOnChampionshipCollection;

use App\Shared\Application\Query\AsQueryHandler;
use App\Team\Domain\Repository\TeamRepositoryInterface;

#[AsQueryHandler]
final readonly class GetAvailableTeamsOnChampionshipQueryHandler
{
    public function __construct(
        private TeamRepositoryInterface $repository
    ) {
    }

    public function __invoke(GetAvailableTeamsOnChampionshipQuery $query): TeamRepositoryInterface
    {
        $repository = $this->repository
            ->withNotAlreadySelected($query->championship)
            ->withOrderByMinValue()
        ;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
