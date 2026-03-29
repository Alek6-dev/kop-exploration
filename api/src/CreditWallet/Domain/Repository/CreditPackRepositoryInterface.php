<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Repository;

use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface CreditPackRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    public function withOrderByCredit(string $direction = 'DESC'): static;
}
