<?php

declare(strict_types=1);

namespace App\Player\Application\Query\Collection;

use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetPlayersQueryHandler
{
    public function __construct(private PlayerRepositoryInterface $repository)
    {
    }

    public function __invoke(GetPlayersQuery $query): PlayerRepositoryInterface
    {
        $repository = $this->repository;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
