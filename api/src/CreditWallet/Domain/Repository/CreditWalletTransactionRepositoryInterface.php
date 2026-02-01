<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Repository;

use App\CreditWallet\Domain\Enum\TransactionType;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface CreditWalletTransactionRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withType(TransactionType $type): static;
}
