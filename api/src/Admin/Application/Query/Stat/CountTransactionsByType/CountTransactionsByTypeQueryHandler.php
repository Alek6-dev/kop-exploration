<?php

declare(strict_types=1);

namespace App\Admin\Application\Query\Stat\CountTransactionsByType;

use App\CreditWallet\Domain\Repository\CreditWalletTransactionRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class CountTransactionsByTypeQueryHandler
{
    public function __construct(
        private CreditWalletTransactionRepositoryInterface $transactionRepository,
    ) {
    }

    public function __invoke(CountTransactionsByTypeQuery $query): int
    {
        return $this->transactionRepository
            ->withType($query->type)
            ->count()
        ;
    }
}
