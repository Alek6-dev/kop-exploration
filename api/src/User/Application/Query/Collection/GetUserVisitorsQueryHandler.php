<?php

declare(strict_types=1);

namespace App\User\Application\Query\Collection;

use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class GetUserVisitorsQueryHandler
{
    public function __construct(private UserVisitorRepositoryInterface $repository)
    {
    }

    public function __invoke(GetUserVisitorsQuery $query): UserVisitorRepositoryInterface
    {
        $repository = $this->repository;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
