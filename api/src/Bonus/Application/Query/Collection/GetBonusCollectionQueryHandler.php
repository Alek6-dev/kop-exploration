<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\Collection;

use App\Bonus\Domain\Repository\BonusRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetBonusCollectionQueryHandler
{
    public function __construct(private BonusRepositoryInterface $repository)
    {
    }

    public function __invoke(GetBonusCollectionQuery $query): BonusRepositoryInterface
    {
        $repository = $this->repository
            ->withType($query->type)
            ->withIsJoker($query->isJoker)
            ->withOrderBySort()
        ;

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $repository = $repository->withPagination($query->page, $query->itemsPerPage);
        }

        return $repository;
    }
}
