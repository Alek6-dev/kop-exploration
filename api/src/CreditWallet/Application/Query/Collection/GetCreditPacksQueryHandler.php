<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Query\Collection;

use App\CreditWallet\Domain\Repository\CreditPackRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetCreditPacksQueryHandler
{
    public function __construct(private CreditPackRepositoryInterface $repository)
    {
    }

    public function __invoke(GetCreditPacksQuery $query): CreditPackRepositoryInterface
    {
        return $this->repository
            ->withOrderByCredit('ASC')
        ;
    }
}
