<?php

declare(strict_types=1);

namespace App\Driver\Application\Query\GetAvailableDriversOnChampionshipCollection;

use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetAvailableDriversOnChampionshipQueryHandler
{
    public function __construct(
        private DriverRepositoryInterface $repository
    ) {
    }

    public function __invoke(GetAvailableDriversOnChampionshipQuery $query): DriverRepositoryInterface
    {
        $repository = $this->repository
            ->withNotAlreadySelected($query->championship)
            ->withReplacementPermanently()
            ->withOrderByMinValue()
        ;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
